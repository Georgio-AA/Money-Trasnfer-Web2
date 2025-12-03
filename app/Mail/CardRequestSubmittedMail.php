<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CardRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $userPhone;
    public $cardAmount;
    public $reviewLink;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $userPhone, $cardAmount, $reviewLink)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPhone = $userPhone;
        $this->cardAmount = $cardAmount;
        $this->reviewLink = $reviewLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Card Request Submission - Review Required',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.card-request-admin',
            with: [
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'userPhone' => $this->userPhone,
                'cardAmount' => $this->cardAmount,
                'reviewLink' => $this->reviewLink,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
