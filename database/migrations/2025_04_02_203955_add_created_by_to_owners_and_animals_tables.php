<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToOwnersAndAnimalsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('owners', function (Blueprint $table) {
            // Add created_by column, nullable to avoid errors with existing records
            $table->integer('created_by')->nullable()->after('updated_at');
            
            // Add foreign key constraint
            $table->foreign('created_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
        });

        Schema::table('animals', function (Blueprint $table) {
            // Add created_by column, nullable to avoid errors with existing records
            $table->integer('created_by')->nullable()->after('updated_at');
            
            // Add foreign key constraint
            $table->foreign('created_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
}