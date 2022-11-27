<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransfertCreate extends Notification
{
    use Queueable;

    protected $transfert, $user_from, $user_to, $libelle, $message, $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transfert, $user_from, $user_to, $libelle, $message, $params = [])
    {
        $this->transfert = $transfert;
        $this->user_from = $user_from;
        $this->user_to   = $user_to;
        $this->libelle   = $libelle;
        $this->message   = $message;
        $this->params    = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->libelle)->markdown('mail.transfert.create', [
            'transfert' => $this->transfert,
            'user_from' => $this->user_from,
            'user_to'   => $this->user_to,
            'message'   => $this->message,
            'params'    => $this->params,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'transfert' => $this->transfert,
            'user_from' => $this->user_from,
            'user_to'   => $this->user_to,
            'libelle'   => $this->libelle,
            'message'   => $this->message,
            'params'    => $this->params,
        ];
    }
}
