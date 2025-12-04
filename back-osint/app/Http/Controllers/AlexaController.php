<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Caso;
use App\AsignacionCaso;
use App\Evidencia;
use App\ActividadCaso;
use App\Usuario;

class AlexaController extends Controller
{
    /**
     * Handle incoming Alexa webhook requests
     */
    public function handleRequest(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Alexa Request:', $request->all());

            // Validate Alexa request (basic validation)
            if (!$this->validateAlexaRequest($request)) {
                return response()->json(['error' => 'Invalid request'], 403);
            }

            // Get request data
            $alexaRequest = $request->input('request');
            $session = $request->input('session');
            $context = $request->input('context');

            // Check for user authentication via Account Linking
            $accessToken = $session['user']['accessToken'] ?? null;

            if (!$accessToken && $alexaRequest['type'] !== 'LaunchRequest') {
                return $this->buildAlexaResponse(
                    'Por favor, vincula tu cuenta en la aplicación de Alexa para usar esta skill.',
                    true,
                    true // shouldLinkAccount
                );
            }

            // Process different request types
            $requestType = $alexaRequest['type'];

            switch ($requestType) {
                case 'LaunchRequest':
                    return $this->handleLaunchRequest($session);

                case 'IntentRequest':
                    return $this->handleIntentRequest($alexaRequest, $session, $accessToken);

                case 'SessionEndedRequest':
                    return $this->handleSessionEndedRequest();

                default:
                    return $this->buildAlexaResponse(
                        'No entendí tu solicitud. Por favor, intenta de nuevo.',
                        true
                    );
            }
        } catch (\Exception $e) {
            Log::error('Alexa Handler Error: ' . $e->getMessage());

            return $this->buildAlexaResponse(
                'Lo siento, hubo un error al procesar tu solicitud.',
                true
            );
        }
    }

    /**
     * Handle Launch Request (when user opens the skill)
     */
    private function handleLaunchRequest($session)
    {
        $accessToken = $session['user']['accessToken'] ?? null;

        if (!$accessToken) {
            $speech = 'Bienvenido al sistema OSINT. Para continuar, por favor vincula tu cuenta en la aplicación de Alexa.';
            return $this->buildAlexaResponse($speech, true, true);
        }

        $speech = 'Bienvenido al sistema OSINT. Puedes pedirme el reporte de un caso diciendo: dame el reporte del caso seguido del código del caso. ¿En qué puedo ayudarte?';

        return $this->buildAlexaResponse($speech, false);
    }

    /**
     * Handle Intent Request
     */
    private function handleIntentRequest($request, $session, $accessToken)
    {
        $intentName = $request['intent']['name'];
        $slots = $request['intent']['slots'] ?? [];

        // Process based on intent
        switch ($intentName) {
            case 'GetCaseReportIntent':
                return $this->getCaseReport($slots, $accessToken);

            case 'GetActiveCasesIntent':
                return $this->getActiveCases($accessToken);

            case 'AMAZON.HelpIntent':
                return $this->getHelp();

            case 'AMAZON.StopIntent':
            case 'AMAZON.CancelIntent':
                return $this->buildAlexaResponse('Hasta luego.', true);

            default:
                return $this->buildAlexaResponse(
                    'No entendí ese comando. Puedes pedirme el reporte de un caso.',
                    false
                );
        }
    }

    /**
     * Get case report by case code
     */
    private function getCaseReport($slots, $accessToken)
    {
        // Validate authentication
        if (!$accessToken) {
            return $this->buildAlexaResponse(
                'Por favor, vincula tu cuenta para acceder a esta información.',
                true,
                true
            );
        }

        // Extract case code from slot
        $caseCode = $slots['CaseCode']['value'] ?? null;

        if (!$caseCode) {
            return $this->buildAlexaResponse(
                'Por favor, especifica el código del caso que deseas consultar.',
                false
            );
        }

        try {
            // Find case by code
            $caso = Caso::where('codigo_caso', $caseCode)->first();

            if (!$caso) {
                return $this->buildAlexaResponse(
                    "No encontré el caso con código {$caseCode}. Por favor, verifica el código.",
                    false
                );
            }

            // Get case details
            $asignados = AsignacionCaso::where('id_caso', $caso->id_caso)
                ->join('usuarios', 'asignaciones_casos.id_usuario', '=', 'usuarios.id_usuario')
                ->select('usuarios.nombre')
                ->get();

            $evidencias = Evidencia::where('id_caso', $caso->id_caso)->count();
            $actividades = ActividadCaso::where('id_caso', $caso->id_caso)->count();

            // Build response speech
            $speech = "Reporte del caso {$caseCode}. ";
            $speech .= "Estado: {$caso->estado}. ";
            $speech .= "Descripción: {$caso->descripcion}. ";

            if ($asignados->count() > 0) {
                $nombres = $asignados->pluck('nombre')->implode(', ');
                $speech .= "Asignado a: {$nombres}. ";
            }

            $speech .= "Tiene {$evidencias} evidencias registradas y {$actividades} actividades. ";

            if ($caso->fecha_creacion) {
                $fecha = \Carbon\Carbon::parse($caso->fecha_creacion)->format('d de F de Y');
                $speech .= "Creado el {$fecha}. ";
            }

            $speech .= "¿Necesitas información de otro caso?";

            return $this->buildAlexaResponse($speech, false);

        } catch (\Exception $e) {
            Log::error('Error getting case report: ' . $e->getMessage());

            return $this->buildAlexaResponse(
                'Hubo un error al obtener el reporte del caso. Por favor, intenta de nuevo.',
                false
            );
        }
    }

    /**
     * Get count of active cases
     */
    private function getActiveCases($accessToken)
    {
        if (!$accessToken) {
            return $this->buildAlexaResponse(
                'Por favor, vincula tu cuenta para acceder a esta información.',
                true,
                true
            );
        }

        try {
            $activeCases = Caso::where('estado', 'Activo')->count();

            $speech = "Actualmente tienes {$activeCases} casos activos. ¿Deseas el reporte de algún caso específico?";

            return $this->buildAlexaResponse($speech, false);

        } catch (\Exception $e) {
            Log::error('Error getting active cases: ' . $e->getMessage());

            return $this->buildAlexaResponse(
                'Hubo un error al obtener los casos activos.',
                false
            );
        }
    }

    /**
     * Get help information
     */
    private function getHelp()
    {
        $speech = 'Puedes pedirme el reporte de un caso diciendo: dame el reporte del caso seguido del código. ';
        $speech .= 'También puedes preguntarme cuántos casos activos hay. ';
        $speech .= '¿Qué deseas hacer?';

        return $this->buildAlexaResponse($speech, false);
    }

    /**
     * Handle session ended
     */
    private function handleSessionEndedRequest()
    {
        return response()->json([
            'version' => '1.0',
            'response' => [
                'shouldEndSession' => true
            ]
        ]);
    }

    /**
     * Validate Alexa request (basic validation)
     * For production, implement full signature validation
     */
    private function validateAlexaRequest($request)
    {
        // Basic validation: check if request has required fields
        if (!$request->has('version') || !$request->has('request')) {
            return false;
        }

        // Check timestamp (request should be within 150 seconds)
        $timestamp = $request->input('request.timestamp');
        if ($timestamp) {
            $requestTime = strtotime($timestamp);
            $currentTime = time();

            if (abs($currentTime - $requestTime) > 150) {
                Log::warning('Alexa request timestamp too old');
                return false;
            }
        }

        // TODO: Implement signature verification for production
        // See: https://developer.amazon.com/docs/custom-skills/host-a-custom-skill-as-a-web-service.html

        return true;
    }

    /**
     * Build Alexa response in proper format
     */
    private function buildAlexaResponse($speech, $shouldEndSession = false, $shouldLinkAccount = false)
    {
        $response = [
            'version' => '1.0',
            'response' => [
                'outputSpeech' => [
                    'type' => 'PlainText',
                    'text' => $speech
                ],
                'shouldEndSession' => $shouldEndSession
            ]
        ];

        // Add account linking card if needed
        if ($shouldLinkAccount) {
            $response['response']['card'] = [
                'type' => 'LinkAccount'
            ];
        }

        return response()->json($response);
    }
}
