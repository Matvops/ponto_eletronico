<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        try {
            DB::beginTransaction();

            $createEnum = "CREATE TYPE status_enum AS ENUM ('INATIVO', 'ATIVO')";
            $sql = "ALTER TABLE time_sheet ADD COLUMN status status_enum NOT NULL DEFAULT 'ATIVO'";

            DB::statement($createEnum);
            DB::statement($sql);

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_sheet', fn(Blueprint $table) => $table->dropColumn('status'));
        $dropType = 'DROP TYPE IF EXISTS status_enum';
        DB::statement($dropType);
    }
};
