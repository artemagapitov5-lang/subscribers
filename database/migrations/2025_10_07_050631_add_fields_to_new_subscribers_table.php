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
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('number')->nullable();
            $table->string('ip-equipment')->nullable();
            $table->date('password')->nullable();
            $table->date('connected_at')->nullable();
        });
    }
};
