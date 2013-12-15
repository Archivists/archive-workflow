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
			$table->integer('sides')->default(1);
			$table->integer('status_id');
			$table->integer('carrier_type_id');
			$table->string('created_by')->default("");
		    $table->string('updated_by')->default("");
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
