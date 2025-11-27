<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BankAccount;

class MicroTransferVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bankAccount;
    public $microAmounts;
    public $estimatedArrival;

    /**
     * Create a new message instance.
     */
    public function __construct(BankAccount $bankAccount, array $microAmounts)
    {
        $this->bankAccount = $bankAccount;
        $this->microAmounts = $microAmounts;
        $this->estimatedArrival = now()->addDays(2)->format('M d, Y');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bank Account Verification - Micro-transfers Initiated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.micro-transfer-verification',
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
