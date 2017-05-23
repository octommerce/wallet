<?php namespace Octommerce\Wallet\Models;

use Model;
use Carbon\Carbon;

/**
 * Transaction Model
 */
class Transaction extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_wallet_transactions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['previous_amount', 'updated_amount'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'user' => 'RainLab\User\Models\User',
    ];
    public $belongsToMany = [];
    public $morphTo = [
        'related' => []
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeCreate()
    {
        $user = $this->user;

        $this->previous_amount = $user->wallet_balance;
        $this->updated_amount = $user->wallet_balance + $this->amount;

        if ($this->updated_amount < 0) {
            throw new ApplicationException('Balance not sufficient.');
        }

        $user->wallet_balance = $this->updated_amount;
        $user->wallet_updated_at = Carbon::now();
        $user->save();
    }

}
