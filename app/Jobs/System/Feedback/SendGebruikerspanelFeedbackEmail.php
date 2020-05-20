<?php

namespace App\Jobs\System\Feedback;

use Mail;
use App\Models\Feedback;
use App\Mail\System\Feedback\GebruikerspanelFeedbackReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendGebruikerspanelFeedbackEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $feedback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, Feedback $feedback)
    {
        $this->email = $email;
        $this->feedback = $feedback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new GebruikerspanelFeedbackReceivedMail(auth()->user(), $this->feedback));
    }
}
