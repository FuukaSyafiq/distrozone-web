<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransaksiExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $transaksiExpired;
    public function __construct($transaksiExpired)
    {
        $this->transaksiExpired = $transaksiExpired;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaksi Expired Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaksi_expired',
            with: [
                'transaksi' => $this->transaksiExpired,
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
