<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Virement extends Mailable
{
    use Queueable, SerializesModels;

    public $virement;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($virement)
    {
        $this->virement = $virement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sujet = "Demande de virement";

        return $this->subject($sujet)->markdown('mail.virement.update', ['virement' => $this->virement]);
    }
}
