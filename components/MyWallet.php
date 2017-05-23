<?php namespace Octommerce\Wallet\Components;

use Auth;
use Cms\Classes\ComponentBase;

class MyWallet extends ComponentBase
{
    public $balance;
    public $transactions;

    public function componentDetails()
    {
        return [
            'name'        => 'myWallet Component',
            'description' => 'Displaying wallet balance and history by user.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $user = Auth::getUser();

        $this->balance = $user->wallet_balance;

        $this->transactions = $user->wallet_transactions;
    }
}
