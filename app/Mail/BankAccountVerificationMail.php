<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BankAccount;

class BankAccountVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bankAccount;
    public $verifyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        $this->verifyUrl = route('bank-accounts.verify-email', [
            'bankAccount' => $bankAccount->id,
            'token' => $bankAccount->verification_token,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Bank Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.bank-account-verification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
