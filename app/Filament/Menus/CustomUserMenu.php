<?php

namespace App\Filament\Menus;

use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Auth;

class CustomUserMenu
{
    public static function get(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return [
            UserMenuItem::make('Почта: ' . $user->email)->url('#'),
            UserMenuItem::make('Выйти')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->action(fn () => Auth::logout()),
        ];
    }
}