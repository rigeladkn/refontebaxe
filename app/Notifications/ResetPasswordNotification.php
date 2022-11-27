<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                ->subject("Réinitialisation mot de passe")
                ->greeting(Carbon::now()->format('H') <= 12 ? 'Bonjour,' : 'Bonsoir,')
                ->line('Difficultés à se connecter ?')
                ->line('Réinitialiser votre mot de passe est facile.')
                
                ->line('Appuyez simplement sur le bouton ci-dessous et suivez les instructions. 
                    Vous serez opérationnel en un rien de temps.')

                ->action('Réinitialiser mon mot de passe', $this->url)
                ->line("Merci d'avoir choisi BaxeTransferMoney !");
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
