<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $connection = 'equipment'; // твоя вторая база
    protected $table = 'devices';

    // Разрешённые к массовому заполнению поля
    protected $fillable = [
        'equipment',
        'serial',
        'inventory',
        'sity',
        'install',
        'date',
        'status',
        'FIO',
        'location',
        'manager',
        'segment', 
        'dealer',
    ];

    // Геттеры для отображения колонок "по-русски"
    public function getEquipmentAttribute($value)
    {
        return $value; // Оборудование
    }

    public function getSerialAttribute($value)
    {
        return $value; // Серийный номер
    }

    public function getInventoryAttribute($value)
    {
        return $value; // Инвентарный номер
    }

    public function getSityAttribute($value)
    {
        return $value; // Город (у тебя колонка sity, наверное имелось ввиду city?)
    }

    public function getInstallAttribute($value)
    {
        return $value; // Место установки
    }

    public function getDateAttribute($value)
    {
        return $value; // Дата установки
    }

    public function getStatusAttribute($value)
    {
        return $value; // Статус
    }

    public function getFioAttribute($value)
    {
        return $value; // Ответственный ФИО
    }

    public function getLocationAttribute($value)
    {
        return $value; // Локация
    }

    public function getManagerAttribute($value)
    {
        return $value; // Менеджер
    }

    public function getSegmentAttribute($value)
    {
        return $value; // Локация
    }

    public function getDealerAttribute($value)
    {
        return $value; // Менеджер
    }
}
