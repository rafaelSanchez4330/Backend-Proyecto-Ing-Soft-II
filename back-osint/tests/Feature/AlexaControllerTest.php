<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Caso;

class AlexaControllerTest extends TestCase
{
    /**
     * Test LaunchRequest without authentication
     */
    public function test_launch_request_without_auth()
    {
        $response = $this->postJson('/api/alexa/webhook', [
            'version' => '1.0',
            'session' => [
                'new' => true,
                'sessionId' => 'test-session',
                'user' => [
                    'userId' => 'test-user'
                ]
            ],
            'request' => [
                'type' => 'LaunchRequest',
                'requestId' => 'test-request-123',
                'timestamp' => now()->toIso8601String()
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'version',
            'response' => [
                'outputSpeech' => [
                    'type',
                    'text'
                ],
                'shouldEndSession'
            ]
        ]);
    }

    /**
     * Test GetCaseReportIntent with valid case code
     */
    public function test_get_case_report_with_valid_code()
    {
        // Create a test case
        $caso = Caso::create([
            'codigo_caso' => 'TEST123',
            'descripcion' => 'Test case description',
            'estado' => 'Activo',
            'fecha_creacion' => now()
        ]);

        $response = $this->postJson('/api/alexa/webhook', [
            'version' => '1.0',
            'session' => [
                'new' => false,
                'sessionId' => 'test-session',
                'user' => [
                    'userId' => 'test-user',
                    'accessToken' => 'test-token'
                ]
            ],
            'request' => [
                'type' => 'IntentRequest',
                'requestId' => 'test-request-456',
                'timestamp' => now()->toIso8601String(),
                'intent' => [
                    'name' => 'GetCaseReportIntent',
                    'slots' => [
                        'CaseCode' => [
                            'name' => 'CaseCode',
                            'value' => 'TEST123'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('response.outputSpeech.text', function ($text) {
            return str_contains($text, 'TEST123');
        });

        // Cleanup
        $caso->delete();
    }

    /**
     * Test GetCaseReportIntent with invalid case code
     */
    public function test_get_case_report_with_invalid_code()
    {
        $response = $this->postJson('/api/alexa/webhook', [
            'version' => '1.0',
            'session' => [
                'new' => false,
                'sessionId' => 'test-session',
                'user' => [
                    'userId' => 'test-user',
                    'accessToken' => 'test-token'
                ]
            ],
            'request' => [
                'type' => 'IntentRequest',
                'requestId' => 'test-request-789',
                'timestamp' => now()->toIso8601String(),
                'intent' => [
                    'name' => 'GetCaseReportIntent',
                    'slots' => [
                        'CaseCode' => [
                            'name' => 'CaseCode',
                            'value' => 'INVALID999'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('response.outputSpeech.text', function ($text) {
            return str_contains($text, 'No encontré');
        });
    }

    /**
     * Test HelpIntent
     */
    public function test_help_intent()
    {
        $response = $this->postJson('/api/alexa/webhook', [
            'version' => '1.0',
            'session' => [
                'new' => false,
                'sessionId' => 'test-session',
                'user' => [
                    'userId' => 'test-user',
                    'accessToken' => 'test-token'
                ]
            ],
            'request' => [
                'type' => 'IntentRequest',
                'requestId' => 'test-request-101',
                'timestamp' => now()->toIso8601String(),
                'intent' => [
                    'name' => 'AMAZON.HelpIntent'
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('response.shouldEndSession', false);
    }

    /**
     * Test request validation with old timestamp
     */
    public function test_request_validation_with_old_timestamp()
    {
        $oldTimestamp = now()->subMinutes(5)->toIso8601String();

        $response = $this->postJson('/api/alexa/webhook', [
            'version' => '1.0',
            'session' => [
                'new' => true,
                'sessionId' => 'test-session',
                'user' => [
                    'userId' => 'test-user'
                ]
            ],
            'request' => [
                'type' => 'LaunchRequest',
                'requestId' => 'test-request-202',
                'timestamp' => $oldTimestamp
            ]
        ]);

        $response->assertStatus(403);
    }
}
