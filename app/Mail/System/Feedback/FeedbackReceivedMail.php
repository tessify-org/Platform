<?php

namespace App\Mail\System\Feedback;

use App\Models\User;
use App\Models\Feedback;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $feedback;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Feedback $feedback)
    {
        $this->user = $user;
        $this->feedback = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("HNNW Beta: feedback ontvangen")
                    ->markdown('emails.system.feedback-received', [
                        'user' => $this->user,
                        'feedback' => $this->feedback,
                        'closingText' => nl2br(__('general.email_closing_text')),
                    ]);
    }
}
