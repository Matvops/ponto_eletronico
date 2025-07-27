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
        Schema::create('users', function (Blueprint $table) {
            $table->id('usr_id')->autoIncrement();
            $table->string('username', 40);
            $table->string('email', 40)->unique();
            $table->string('password', 100);
            $table->enum('role', ['USER', 'ADMIN']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
