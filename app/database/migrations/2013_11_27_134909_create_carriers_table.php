<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carriers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('shelf_number');
			$table->integer('parts')->default(0);
			$table->integer('sides')->default(0);
			$table->integer('carrier_type_id');
			$table->string('created_by');
		    $table->string('updated_by');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('carriers');
	}

}
