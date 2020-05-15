<?php

namespace App\Mail\Auth;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecoverAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('auth.recover_account_email_subject'))
                    ->markdown('emails.auth.recover-account', [
                        'user' => $this->user,
                        'titleText' => __('auth.recover_account_email_title'),
                        'buttonText' => __('auth.recover_account_email_button'),
                        'closingText' => nl2br(__('general.email_closing_text')),
                        'text' => str_replace('\n', '<br>', (__('auth.recover_account_email_text', ['name' => 'henk']))),
                    ]);
    }
}
