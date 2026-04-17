<?php

namespace App\Services\Xendit;

use App\Models\Sku;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;

class XenditPaymentService
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    public function createInvoice(array $orderDetails, $order): string
    {
        $items = [];
        foreach ($orderDetails as $item) {
            $sku = Sku::find($item['sku_id']);
            $items[] = new InvoiceItem([
                'name' => $sku->name,
                'quantity' => (int) $item['qty'],
                'price' => (int) $sku->price,
            ]);
        }

        $externalId = 'ORDER-' . $order->id . '-' . time();

        $request = new CreateInvoiceRequest([
            'external_id' => $externalId,
            'amount' => (int) $order->total_price,
            'payer_email' => $order->user->email,
            'description' => 'Order #' . $order->id,
            'invoice_duration' => (int) config('xendit.invoice_duration', 3600),
            'currency' => config('xendit.currency', 'IDR'),
            'items' => $items,
            'success_redirect_url' => url('/api/payment/finish?status=success&order_id=' . $order->id),
            'failure_redirect_url' => url('/api/payment/finish?status=failed&order_id=' . $order->id),
        ]);

        $invoice = $this->invoiceApi->createInvoice($request);

        return $invoice->getInvoiceUrl();
    }
}
