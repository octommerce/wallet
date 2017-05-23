<?php namespace Octommerce\Wallet\Components;

use Auth;
use Cms\Classes\ComponentBase;

class CheckoutWallet extends ComponentBase
{
    public $maxBalance;

    public function componentDetails()
    {
        return [
            'name'        => 'checkoutWallet Component',
            'description' => 'Apply wallet balance for checkout.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $user = Auth::getUser();

        $this->maxBalance = $user->wallet_balance;
    }
}
