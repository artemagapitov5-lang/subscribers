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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('fio'); // ФИО
            $table->string('city')->nullable(); // Город
            $table->string('address')->nullable(); // Адрес
            $table->string('service')->nullable(); // Услуга
            $table->string('login')->nullable(); // Логин
            $table->string('band')->nullable(); // Гром Полоса
            $table->string('cabinet1')->nullable(); // Шкаф 1
            $table->string('cabinet2')->nullable(); // Шкаф 2
            $table->string('switch_address')->nullable(); // Адрес Комутатора
            $table->string('port')->nullable(); // Порт
            $table->string('active')->nullable(); // Активен
            $table->text('note')->nullable(); // Примечание
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
