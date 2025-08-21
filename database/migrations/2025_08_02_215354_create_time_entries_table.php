<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_sheet', function (Blueprint $table) {
            $table->id('tis_id')->autoIncrement();
            $table->foreignId('tis_usr_id')
                    ->references('usr_id')
                    ->on('users');
            $table->timestamp('date');
            $table->enum('type', ['ENTRADA', 'SAIDA']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_sheet');
    }
};
