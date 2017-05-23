<?php namespace Octommerce\Wallet;

use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Octommerce\Octommerce\Models\Order;

/**
 * Shipping Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octommerce.Octommerce'];

    public function registerComponents()
    {
        return [
            'Octommerce\Wallet\Components\MyWallet' => 'myWallet',
        ];
    }

    public function boot()
    {
        User::extend(function ($model) {
            $model->hasMany['wallet_transactions'] = 'Octommerce\Wallet\Models\Transaction';
        });

        Order::extend(function ($model) {
            $model->morphOne['transaction'] = [
                'Octommerce\Wallet\Models\Transaction',
                'name' => 'related',
            ];
        });
    }
}
