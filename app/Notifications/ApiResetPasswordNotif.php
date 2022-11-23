<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ApiResetPasswordNotif extends Notification
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
        ->subject("Réinitialisation de mot de passe")
        ->greeting(Carbon::now()->format('H') <= 12 ? 'Bonjour,' : 'Bonsoir,')
        ->line('Difficultés à se connecter ?')
        ->line('Réinitialiser votre mot de passe est facile.')
        ->line('Utiliser le code ci-dessous pour réinitialiser votre mot de pass.
            Et vous serez opérationnel en un rien de temps.')
        ->line('Votre code de réinitialisation est : '.$this->donnees['code'])
        ->line("Merci d'avoir choisi Baxe Money Transfer !");
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
