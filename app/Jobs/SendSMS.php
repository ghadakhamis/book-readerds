<?php

namespace App\Jobs;

use App\Integrations\LocalSMSProvider;
use App\Integrations\ProductionSMSProvider;
use App\Interfaces\SMSProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone, $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phone, string $message)
    {
        $this->onQueue('sms');
        $this->phone   = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var SMSProvider $smsProvider */
        $smsProvider = App::environment('local')? app(LocalSMSProvider::class) : app(ProductionSMSProvider::class);
        $response = $smsProvider->send($this->phone, $this->message);
        // TODO log $response and make processor on it to handel failer cases
    }
}
