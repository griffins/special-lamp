<?php

namespace App\Notifications;

use App\Foundation\SmsChannel;
use App\Foundation\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(sprintf('Howdy %s, you have been invited to join %s.',
                explode(' ', $notifiable->name)[0],
                $notifiable->club))
            ->line(sprintf('Just some heads up.'))
            ->line(sprintf('You temporary password is %s.', $this->password))
            ->action(sprintf('Go to %s', config('app.name')), url('/'))
            ->line(sprintf('Thank you for using %s!', config('app.name')));
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SmsMessage
     */

    public function toSms($notifiable)
    {
        $name = explode(' ', $notifiable->name)[0];
        $user = explode(' ', auth()->user()->name)[0];
        $app = config('app.name');

        return (new SmsMessage("Howdy $name, you have been nominated by $user as an administrator for $app. You temporary password is {$this->password}."))->unicode();
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
