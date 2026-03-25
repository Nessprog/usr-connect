<?php

namespace App\Notifications;

use App\Models\Slot;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionReminder extends Notification
{
    use Queueable;

    protected $slot;

    public function __construct(Slot $slot)
    {
        $this->slot = $slot;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Rappel : Ta mission Solida\'Foot approche ! ⚽')
            ->greeting('Salut ' . $notifiable->name . ' !')
            ->line('Ceci est un petit rappel pour ta mission de bénévole.')
            ->line('**Pôle :** ' . $this->slot->category)
            ->line('**Mission :** ' . $this->slot->title)
            ->line('**Horaire :** de ' . $this->slot->start_time->format('H:i') . ' à ' . $this->slot->end_time->format('H:i'))
            ->action('Voir mon planning', url('/dashboard'))
            ->line('Merci pour ton aide précieuse !')
            ->salutation('L\'équipe Solida\'Foot');
    }
}
