<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;

class LogJobProcessing
{
    public function handle(JobProcessing $event)
    {
        try {
            $payload = method_exists($event->job, 'payload') ? $event->job->payload() : null;
        } catch (\Throwable $e) {
            $payload = null;
        }

        $display = null;
        if (is_array($payload)) {
            $display = $payload['displayName'] ?? ($payload['job'] ?? null);
        }

        Log::info('Job processing started.', [
            'job' => $display ?? get_class($event->job),
            'connection' => $event->connectionName ?? null,
            'payload' => $payload,
        ]);
    }
}
