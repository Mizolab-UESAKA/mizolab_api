<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makers', function (Blueprint $table) {
            $table->id()->comment('メーカーID');
            $table->string('name')->unique()->comment('メーカー名');
            $table->tinyInteger('country')->nullable()->comment('国（1:日本車, 2:外車）');
            $table->string('maker_logo_path')->comment('ロゴ画像パス');
            $table->tinyInteger('disp_num')->default(99)->comment('表示順');
            $table->boolean('status')->default(1)->comment('ステータス（0:削除, 1:有効）');
            $table->dateTime('created_at')->useCurrent()->comment('作成日');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日');
            $table->unsignedBigInteger('created_by')->nullable()->comment('作成者');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('更新者');
        });
        DB::statement("ALTER TABLE `makers` COMMENT 'メーカー'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('makers');
    }
}
