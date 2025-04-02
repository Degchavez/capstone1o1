<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('reports');

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('report_type');
            $table->string('file_path')->nullable();
            $table->datetime('date_from');
            $table->datetime('date_to');
            $table->json('parameters')->nullable();
            $table->string('status')->default('completed');
            $table->integer('generated_by');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('generated_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};