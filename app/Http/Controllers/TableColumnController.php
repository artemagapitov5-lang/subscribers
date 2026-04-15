<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;

class TableColumnController extends Controller
{
    private $allColumns = [
        'fio', 'city', 'address', 'service', 'login', 'number', 'ip', 'password',
        'band', 'cabinet1', 'cabinet2', 'switch_address', 'port', 'active', 'note', 'date'
    ];

    public function index()
    {
        // Можно сохранять пользовательские настройки колонок в сессии или базе
        $userColumns = session('user_columns', $this->allColumns);
        return view('table.index', compact('userColumns'));
    }

    public function save(Request $request)
    {
        $columns = $request->input('columns', $this->allColumns);
        // Сохраняем в сессию для примера (можно в БД)
        session(['user_columns' => $columns]);
        return response()->json(['success' => true]);
    }

    public function data(Request $request)
    {
        $columns = session('user_columns', $this->allColumns);

        $query = Subscriber::select($columns);

        return datatables()->of($query)->toJson();
    }
}