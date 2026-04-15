@php
    $user = auth()->user();
@endphp

@if($user)
<div class="relative inline-block text-left group">
    <button class="flex items-center gap-2 bg-blue-500 text-white px-3 py-1 rounded-full">
        <div class="w-8 h-8 flex items-center justify-center bg-white text-blue-500 rounded-full font-bold">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <span>{{ $user->email }}</span>
        <!-- стрелка вниз -->
        <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Выпадающее меню -->
    <div class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all">
        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Профиль</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Выйти</button>
        </form>
    </div>
</div>
@endif