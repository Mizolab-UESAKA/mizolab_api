<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDataEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_entities', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('place_id')->nullable();
            $table->string('device_sn')->nullable();
            $table->string('data_type')->nullable();
            $table->string('port_number')->nullable();
            $table->string('sensor_name')->nullable();
            $table->string('units')->nullable();
            $table->dateTime('created_at')->useCurrent()->comment('作成日');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日');
            $table->unsignedBigInteger('created_by')->nullable()->comment('作成者');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('更新者');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_entities');
    }
}
