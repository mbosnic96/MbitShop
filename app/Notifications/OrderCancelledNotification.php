<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderCancelledNotification extends Notification
{
    use Queueable;
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Store in the database and send email
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Narudžba #{$this->order->order_number} je otkazana.",
            'order_number' => $this->order->order_number,
            'user_photo' => $this->order->user->profile_photo_path,
            'order_id' => $this->order->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Narudžba #'.$this->order->order_number.' Otkazana')
            ->line('Vaša naružba je otkazana.')
            ->line('Broj narudžbe: ' . $this->order->order_number)
            ->line('Cijena: KM' . number_format($this->order->total_price, 2))
            ->action('Vidi narudžbu', url('dashboard/orders?search=' . $this->order->order_number));
    }
}