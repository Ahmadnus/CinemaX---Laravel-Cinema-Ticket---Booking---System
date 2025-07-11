<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    public function askChatGPT(string $message): string
{
    $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'أنت مساعد ذكي يقترح أفلام حسب مزاج المستخدم.'],
            ['role' => 'user', 'content' => $message],
        ],
    ]);

    if (!$response->successful()) {
        // مثلا ترجع رسالة خطأ بدل ما تفشل
        throw new \Exception('Failed to connect to OpenAI: ' . $response->body());
    }

    $content = $response->json('choices.0.message.content');

    if (is_null($content)) {
        throw new \Exception('OpenAI response does not contain expected data.');
    }

    return $content;
}
}