<?php

namespace App\Notifications;

use App\Models\Material;
use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Material $material, public PurchaseRequisition $requisition) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low stock: '.$this->material->name)
            ->line("{$this->material->name} is at or below its minimum. Purchase request {$this->requisition->number} was created.")
            ->action('Review requisition', url('/admin/purchase-requisitions'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Low stock',
            'material' => $this->material->name,
            'requisition' => $this->requisition->number,
        ];
    }
}
