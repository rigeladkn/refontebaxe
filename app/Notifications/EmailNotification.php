<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class EmailNotification extends Notification
{
    use Queueable;

    /**
     * @var array $project
     */
    protected $donnees;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($donnees)
    {
        $this->donnees = $donnees;
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
        return (new MailMessage)

            ->greeting(Carbon::now()->format('H') <= 12 ? 'Bonjour,' : 'Bonsoir,')
            ->subject($this->donnees['subject'])
            ->greeting($this->donnees['greeting'])
            ->line($this->donnees['body'])
            ->action($this->donnees['actionText'], $this->donnees['actionURL'])
            ->line($this->donnees['thanks'])
            ->Salutation('Cordialement');

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
            //
        ];
    }
}
