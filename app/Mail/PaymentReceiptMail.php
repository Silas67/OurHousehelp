<?php

namespace App\Mail;

use App\Models\RegistrationPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(RegistrationPayment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('Payment Receipt - Registration Payment')
            ->view('emails.payment-receipt')
            ->with([
                'payment' => $this->payment,
                'user' => $this->payment->user,
            ]);
    }
}
