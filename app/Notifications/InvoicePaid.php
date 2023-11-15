<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @type @string
     */
    public $message;


    /**
     * Recipient Mail address
     */
    public $Email;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message ,string $email)
    {
        $this->message = $message;
        $this->Email = $email;
        $this->afterCommit(); //ensure db committed
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //  return ['database'];
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /** CUSTUME ADDED 
     * Undocumented function
     *
     * @param object $notifiable
     * @return void
     */
    public function withDelay(object $notifiable)
    {
        return [
            "sms" => now()->addSeconds(3),
            "database" => now()->addSeconds(2),
            "mail" => now()->addSeconds(1)
        ];
    }


    /**
     * CUSTUME
     * CHECK IF THIS NOT SHOULD BE SENT
     */
    public function shouldSend(object $nitifiable, string $channel): bool
    {
        
        return true;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
