<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastReadAtToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('last_read_at_buyer')->nullable()->after('status');
            $table->timestamp('last_read_at_seller')->nullable()->after('last_read_at_buyer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('last_read_at_buyer')->nullable()->after('status');
            $table->timestamp('last_read_at_seller')->nullable()->after('last_read_at_buyer');
        });
    }
}
