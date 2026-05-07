<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinatarios', function (Blueprint $table) {
            $table->tinyInteger('red_udeg')->boolean();
        });
    }

    public function down(): void
    {
        Schema::table('destinatarios', function (Blueprint $table) {
             $table->tinyInteger('red_udeg')->boolean();
        });
    }
};
