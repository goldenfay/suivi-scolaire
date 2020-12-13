<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParentNotification extends Notification
{
    use Queueable;
    protected $notifiable_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification=$notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $greeting=((int)date('H') )>15?"Bonsoir":"Bonjour";
        $civilite=$notifiable->Cvilite==null?"Mr/Mme":$notifiable->Cvilite;
        return (new MailMessage)
                    
                    ->subject('Scolarité')
                    ->greeting("$greeting $civilite $notifiable->Nom")
                    ->line('Nous voulons vous informer que votre fils vient de recevoir une observations.')
                    ->line('Vous trouverez cette dernière dans son cahier de correspondance numérique.')
                    ->action('Consulter', url('/'))
                    ->line('Cordialement.');
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
