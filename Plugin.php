<?php namespace Octommerce\Wallet;

use Event;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Responsiv\Pay\Models\InvoiceItem;
use Octommerce\Octommerce\Models\Order;
use Octommerce\Wallet\Models\Transaction;

/**
 * Shipping Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octommerce.Octommerce'];

    public function registerComponents()
    {
        return [
            'Octommerce\Wallet\Components\MyWallet'       => 'myWallet',
            'Octommerce\Wallet\Components\CheckoutWallet' => 'checkoutWallet',
        ];
    }

    public function boot()
    {
        User::extend(function ($model) {
            $model->hasMany['wallet_transactions'] = 'Octommerce\Wallet\Models\Transaction';
        });

        Order::extend(function ($model) {
            $model->addFillable([
                'wallet_used',
            ]);

            $model->morphOne['transaction'] = [
                'Octommerce\Wallet\Models\Transaction',
                'name' => 'related',
            ];
        });

        Event::listen('order.beforeAddInvoice', function($order, $invoice) {
            if ($order->wallet_used <= 0)
                return;

            $discountItem = new InvoiceItem([
                'description' => 'Wallet Used',
                'quantity' => 1,
                'price' => 0,
                'discount' => $order->wallet_used,
            ]);

            $invoice->items()->save($discountItem);

            Transaction::create([
                'user' => $order->user,
                'description' => 'Checkout Order #' . $order->order_no,
                'amount' => -$order->wallet_used,
                'related' => $order,
            ]);
        });
    }
}
