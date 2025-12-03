<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $beneficiaryName;
    public $senderName;
    public $amount;
    public $currency;
    public $transferReference;

    /**
     * Create a new message instance.
     */
    public function __construct($beneficiaryName, $senderName, $amount, $currency, $transferReference)
    {
        $this->beneficiaryName = $beneficiaryName;
        $this->senderName = $senderName;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->transferReference = $transferReference;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Money Transfer Received - SWIFTPAY',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transfer-notification',
            with: [
                'beneficiaryName' => $this->beneficiaryName,
                'senderName' => $this->senderName,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'transferReference' => $this->transferReference,
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
