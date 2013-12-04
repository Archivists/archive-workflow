<?php

use Illuminate\Database\Migrations\Migration;

class AddNotesToCarriersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('carriers', function($table)
		{
		    $table->string('notes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('carriers', function($table)
		{
		    $table->dropColumn('notes');
		});
	}

}