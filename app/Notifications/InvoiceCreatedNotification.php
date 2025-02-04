<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

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
        // return ['mail', 'database','broadcast'];
        return ['database', 'broadcast'];
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
            'user' => Auth::user()->name,
            'section_id' => $this->invoice->section->id,
            'amount_collection' => $this->invoice->amount_collection,
            'amount_commission' => $this->invoice->amount_commission,
            'discount' => $this->invoice->discount,
            'value_VAT' => $this->invoice->value_VAT,
            'rate_VAT' => $this->invoice->rate_VAT,
            'total' => $this->invoice->total,
            'status' => $this->invoice->status,
            'note' => $this->invoice->note,
            'payment_date' => $this->invoice->payment_date,
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'تم اضافة فاتورة جديدة بواسطة',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'invoice_date' => $this->invoice->invoice_date,
            'due_date' => $this->invoice->due_date,
            'product' => $this->invoice->product,
            'user' => Auth::user()->name,
            'section_id' => $this->invoice->section->id,
            'amount_collection' => $this->invoice->amount_collection,
            'amount_commission' => $this->invoice->amount_commission,
            'discount' => $this->invoice->discount,
            'value_VAT' => $this->invoice->value_VAT,
            'rate_VAT' => $this->invoice->rate_VAT,
            'total' => $this->invoice->total,
            'status' => $this->invoice->status,
            'note' => $this->invoice->note,
            'payment_date' => $this->invoice->payment_date,
        ]);
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

    public function broadcastOn()
    {
        // Broadcasting to the shared owner-notifications channel
        return new PrivateChannel('owner-notifications');
    }

}
