<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddInvoice extends Notification
{
    use Queueable;
    private $invoice_id;

    public function __construct($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/InvoicesDetails/' . $this->invoice_id);
        return (new MailMessage)
            ->greeting('مرحبا')
            ->subject('HaweilInvoiceHub')
            ->line('تم اضافة فاتورة جديدة')
            ->action('عرض الفاتورة', $url)
            ->line('شكرا لاستخدامكم نظام الفواتير');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}