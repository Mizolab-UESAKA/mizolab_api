<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatezentraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zentra', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('value_type_id')->nullable();
            $table->string('time')->nullable();
            $table->string('value')->nullable();
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
        Schema::dropIfExists('zentra');
    }
}
