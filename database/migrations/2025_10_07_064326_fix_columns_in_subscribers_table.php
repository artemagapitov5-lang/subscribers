<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            // Меняем ip-equipment на ip (Laravel не поддерживает дефисы)
            $table->renameColumn('ip-equipment', 'ip');
            
            // Исправим password — дата ему не подходит, пусть будет строка
            $table->string('password', 255)->nullable()->change();

            // Переименуем connected_at в connected_date для наглядности
            $table->renameColumn('connected_at', 'date');
        });
    }

    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->renameColumn('ip', 'ip-equipment');
            $table->date('password')->change();
            $table->renameColumn('date', 'connected_at');
        });
    }
};
