<?php

use Illuminate\Database\Seeder;

class ChatbotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Chatbot Telegram
        DB::table('chatbot_telegram')->insert([
            [
                'telegram_user_id' => 123456789,
                'user_id' => 1,
                'linked_at' => now()
            ],
            [
                'telegram_user_id' => 987654321,
                'user_id' => 2,
                'linked_at' => now()->subDays(5)
            ],
            [
                'telegram_user_id' => 555666777,
                'user_id' => 3,
                'linked_at' => now()->subDays(10)
            ]
        ]);

        // Chatbot Alexa
        DB::table('chatbot_alexa')->insert([
            [
                'alexa_user_id' => 'amzn1.ask.account.ADMIN123',
                'user_id' => 1,
                'linked_at' => now(),
                'preferences' => json_encode(['language' => 'es-ES', 'notifications' => true])
            ],
            [
                'alexa_user_id' => 'amzn1.ask.account.MARIA456',
                'user_id' => 3,
                'linked_at' => now()->subDays(3),
                'preferences' => json_encode(['language' => 'es-MX', 'notifications' => false])
            ],
            [
                'alexa_user_id' => 'amzn1.ask.account.CARLOS789',
                'user_id' => 4,
                'linked_at' => now()->subDays(7),
                'preferences' => null
            ]
        ]);

        // Chatbot WhatsApp
        DB::table('chatbot_whatsapp')->insert([
            [
                'whatsapp_id' => 'wa_admin_001',
                'user_id' => 1,
                'linked_at' => now()
            ],
            [
                'whatsapp_id' => 'wa_carlos_002',
                'user_id' => 4,
                'linked_at' => now()->subDays(2)
            ],
            [
                'whatsapp_id' => 'wa_ana_003',
                'user_id' => 5,
                'linked_at' => now()->subDays(15)
            ]
        ]);
    }
}
