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
            $table->text('source_type')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        if (Schema::hasColumns('users', ['credit_balance', 'credit_updated_at'])) {
            return;
        }

        Schema::table('users', function($table)
        {
            $table->decimal('credit_balance', 12, 2)->unsigned()->nullable();
            $table->timestamp('credit_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_wallet_transactions');

        Schema::table('users', function($table) {
            $table->dropColumn('credit_balance');
            $table->dropColumn('credit_updated_at');
        });
    }
}
