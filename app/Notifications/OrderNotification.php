<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderNotification extends Notification 
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database','mail']; // Store in the database
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Nova narudžba #{$this->order->order_number} korisnika {$this->order->user->name}.",
            'order_number' => $this->order->order_number,
            'user_photo' => $this->order->user->profile_photo_path,
            'order_id' => $this->order->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Nova narudžba #' . $this->order->order_number)
            ->line('Imate novu narudžbu.')
            ->line('Broj narudžbe: ' . $this->order->order_number)
            ->line('Ukupna cijena: KM' . number_format($this->order->total_price, 2))
            ->action('Pogledajte narudžbu', url('dashboard/orders?search=' . $this->order->order_number));
    }

}