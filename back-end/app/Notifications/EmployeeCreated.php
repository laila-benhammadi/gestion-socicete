<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable; // Permet de mettre cette notification dans une file d'attente.
use Illuminate\Notifications\Notification; // Classe de base pour les notifications.
use Illuminate\Contracts\Queue\ShouldQueue; // Interface pour indiquer que cette notification peut être mise en file d'attente.
use Illuminate\Notifications\Messages\MailMessage; // Permet de créer des messages d’e-mail.

class EmployeeCreated extends Notification
{
    use Queueable; // Active la gestion des files d'attente.

    protected $firstName;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param string $firstName
     * @param string $password
     */
    public function __construct($firstName, $password)
    {
        $this->firstName = $firstName;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Utilise l'e-mail comme canal.
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
            ->subject('Nouvel employé ajouté')
            ->greeting('Bonjour ' . htmlentities($this->firstName, ENT_QUOTES, 'UTF-8'))
            ->line('Votre compte a été créé avec succès.')
            ->line('Votre mot de passe est : ' . htmlentities($this->password, ENT_QUOTES, 'UTF-8'))
            ->action('Se connecter', url('http://localhost:5173/login'))
            ->from(config('mail.from.address'), config('mail.from.name'));
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
            'message' => 'Un nouvel employé a été ajouté.',
        ];
    }
}
