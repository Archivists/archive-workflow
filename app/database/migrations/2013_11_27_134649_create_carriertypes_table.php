<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriertypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carrier_types', function(Blueprint $table)
		{
			$table->increments('id')->default(0);
			$table->string('name');
			$table->string('description');
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
		Schema::drop('carrier_types');
	}

}
