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
    protected $guarded = ['*'];

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

    public function afterCreate()
    {
        $user = $this->user;

        $user->credit_balance = $this->amount;
        $user->credit_updated_at = Carbon::now();
        $user->save();
    }

}
