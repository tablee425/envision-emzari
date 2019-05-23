<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeysForCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		Schema::table('company_user', function (Blueprint $table) {
			$table->integer('company_id')->unsigned()->change();
			$table->integer('user_id')->unsigned()->change();

			$table->foreign('company_id')
				->references('id')->on('companies')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
		});

		Schema::table('areas', function ($table) {
			$table->integer('company_id')->unsigned()->change();
			$table->foreign('company_id')
				->references('id')->on('companies')
				->onDelete('cascade');
		});

		Schema::table('fields', function (Blueprint $table) {
			$table->integer('area_id')->unsigned()->change();
			$table->foreign('area_id')
				->references('id')->on('areas')
				->onDelete('cascade');
		});
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('company_user', function (Blueprint $table) {
			$table->integer('company_id')->change();
			$table->integer('user_id')->change();
			$table->dropForeign('company_user_company_id_foreign');
			$table->dropForeign('company_user_user_id_foreign');
		});

		Schema::table('areas', function ($table) {
			$table->integer('company_id')->change();
			$table->dropForeign('areas_company_id_foreign');
		});


		Schema::table('fields', function ($table) {
			$table->integer('area_id')->change();
			$table->dropForeign('fields_area_id_foreign');
		});



    }
}
