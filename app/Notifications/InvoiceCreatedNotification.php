<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Invoice $invoice)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail', 'database'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Invoice Created Successfully #{$this->invoice->invoice_number}")
            ->greeting("Hello $notifiable->name")
            ->line("a new invoice was created at {$this->invoice->created_at}.")
            ->action('check it now', url("/invoices-details/{$this->invoice->id}"))
            ->line('Thank you for using our application!');
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'تم اضافة فاتورة جديدة بواسطة',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'invoice_date' => $this->invoice->invoice_date,
            'due_date' => $this->invoice->due_date,
            'product' => $this->invoice->product,
        ];
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
