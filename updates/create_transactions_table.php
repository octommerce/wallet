<?php namespace Octommerce\Wallet\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('octommerce_wallet_transactions', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('description')->nullable();
            $table->decimal('previous_amount', 12, 2)->unsigned()->default(0);
            $table->decimal('updated_amount', 12, 2)->unsigned();
            $table->decimal('amount', 12, 2);
            $table->string('related_type')->nullable();
            $table->integer('related_id')->nullable()->unsigned();
            $table->string('status');
            $table->timestamps();
        });

        if (Schema::hasColumns('orders', ['wallet_used'])) {
            return;
        }

        Schema::table('octommerce_octommerce_orders', function($table)
        {
            $table->decimal('wallet_used', 12, 2)->unsigned()->nullable()->after('total');
        });

        if (Schema::hasColumns('users', ['wallet_balance', 'wallet_updated_at'])) {
            return;
        }

        Schema::table('users', function($table)
        {
            $table->decimal('wallet_balance', 12, 2)->unsigned()->nullable();
            $table->timestamp('wallet_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_wallet_transactions');

        Schema::table('octommerce_octommerce_orders', function($table) {
            $table->dropColumn('wallet_used');
        });

        Schema::table('users', function($table) {
            $table->dropColumn('wallet_balance');
            $table->dropColumn('wallet_updated_at');
        });
    }
}
