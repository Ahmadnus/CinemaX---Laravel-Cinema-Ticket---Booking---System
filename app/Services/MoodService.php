<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;
class MoodService
{
    public function getGenreFromMood(string $message): array
    {
        $scriptPath = base_path('app/scripts/mood_classifier.py');

        $command = "python \"$scriptPath\" " . escapeshellarg($message);

        $process = Process::run($command);

        if ($process->successful()) {
            return json_decode($process->output(), true) ?? [['mood' => 'neutral', 'recommended_genre' => 'comedy']];
        }

        return [['mood' => 'neutral', 'recommended_genre' => 'comedy']];
    }
}