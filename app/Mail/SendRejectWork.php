<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRejectWork extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $jobVacancy;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $jobVacancy)
    {
        $this->user = $user;
        $this->jobVacancy = $jobVacancy;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Penolakan Lamaran Pekerjaan',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.work.reject',
            with: [$this->user, $this->jobVacancy]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
