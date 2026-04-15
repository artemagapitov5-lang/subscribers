<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    // Указываем, какие поля можно массово заполнять
    protected $fillable = [
        'fio', 'city', 'address', 'service', 'login', 'number', 'ip', 'password', 'band', 'cabinet1', 'cabinet2',
        'switch_address', 'port', 'active', 'note', 'date',
    ];

    // Метод для отображения ФИО на русском
    public function getFioAttribute($value)
    {
        return $value;
    }

    // Метод для отображения города на русском
    public function getCityAttribute($value)
    {
        return $value;
    }

    // Метод для отображения адреса на русском
    public function getAddressAttribute($value)
    {
        return $value;
    }

    // Метод для отображения телефона на русском
    public function getServiceAttribute($value)
    {
        return $value;
    }

    // Метод для отображения логина на русском
    public function getLoginAttribute($value)
    {
        return $value;
    }
    
    // Метод для отображения ширины полосы на русском
    public function getNumberAttribute($value)
    {
        return $value;
    }

    // Метод для отображения ширины полосы на русском
    public function getIpAttribute($value)
    {
        return $value;
    }

    // Метод для отображения ширины полосы на русском
    public function getPasswordAttribute($value)
    {
        return $value;
    }

    // Метод для отображения ширины полосы на русском
    public function getBandAttribute($value)
    {
        return $value;
    }

    // Метод для отображения шкафа 1 на русском
    public function getCabinet1Attribute($value)
    {
        return $value;
    }

    // Метод для отображения шкафа 2 на русском
    public function getCabinet2Attribute($value)
    {
        return $value;
    }

    // Метод для отображения адреса коммутатора на русском
    public function getSwitchAddressAttribute($value)
    {
        return $value;
    }

    // Метод для отображения порта на русском
    public function getPortAttribute($value)
    {
        return $value;
    }

    // Метод для отображения статуса "Активен" на русском
    public function getActiveAttribute($value)
    {
        return $value;
    }

    // Метод для отображения примечания на русском
    public function getNoteAttribute($value)
    {
        return $value;
    }

    // Метод для отображения ширины полосы на русском
    public function getDateAttribute($value)
    {
        return $value;
    }

    protected $casts = [
     'active' => 'boolean',
    ];
}
