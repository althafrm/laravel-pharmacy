<?php

namespace App\Jobs;

use App\Notifications\QuotationUpdatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class QuotationUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $notifiable, $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable, $data)
    {
        $this->notifiable = $notifiable;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send(
            $this->notifiable,
            new QuotationUpdatedNotification($this->data)
        );
    }
}
