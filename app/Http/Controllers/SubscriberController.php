<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Exports\SubscribersExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    // Отображение списка абонентов с фильтрацией
    public function index(Request $request)
    {
        $subscribers = Subscriber::query();

        if ($request->filled('fio')) {
            $subscribers->where('fio', 'like', '%' . $request->fio . '%');
        }
        if ($request->filled('city')) {
            $subscribers->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->filled('service')) {
            $subscribers->where('service', 'like', '%' . $request->service . '%');
        }
        if ($request->filled('login')) {
            $subscribers->where('login', 'like', '%' . $request->login . '%');
        }
        if ($request->filled('number')) {
            $subscribers->where('number', 'like', '%' . $request->number . '%');
        }
        if ($request->filled('ip')) {
            $subscribers->where('ip', 'like', '%' . $request->ip . '%');
        }
        if ($request->filled('password')) {
            $subscribers->where('password', 'like', '%' . $request->password . '%');
        }
        if ($request->filled('band')) {
            $subscribers->where('band', 'like', '%' . $request->band . '%');
        }
        if ($request->filled('cabinet1')) {
            $subscribers->where('cabinet1', 'like', '%' . $request->cabinet1 . '%');
        }
        if ($request->filled('cabinet2')) {
            $subscribers->where('cabinet2', 'like', '%' . $request->cabinet2 . '%');
        }
        if ($request->filled('switch_address')) {
            $subscribers->where('switch_address', 'like', '%' . $request->switch_address . '%');
        }
        if ($request->filled('port')) {
            $subscribers->where('port', 'like', '%' . $request->port . '%');
        }
        if ($request->filled('active')) {
            $subscribers->where('active', $request->active);
        }
        if ($request->filled('note')) {
            $subscribers->where('note', 'like', '%' . $request->note . '%');
        }
        if ($request->filled('date')) {
            $subscribers->where('date', 'like', '%' . $request->date . '%');
        }

        $subscribers = $subscribers->get();

        return view('subscribers.index', compact('subscribers'));
    }

    // Главная страница
    public function main()
    {
        return view('equipment.index'); // resources/views/subscribers/main.blade.php
    }

    // Показывает форму для создания нового абонента
    public function create()
    {
        return view('subscribers.create');
    }

    // Сохраняет нового абонента в базу данных
    public function store(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'login' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'ip' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'band' => 'nullable|string|max:255',
            'cabinet1' => 'nullable|string|max:255',
            'cabinet2' => 'nullable|string|max:255',
            'switch_address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:255',
            'active' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'date' => 'nullable|string',
        ]);

        $subscriber = Subscriber::create($request->all());

        return response()->json($subscriber);
    }

    // Показывает форму для редактирования абонента
    public function edit(Subscriber $subscriber)
    {
        return view('subscribers.edit', compact('subscriber'));
    }

    // Обновляет данные абонента
    public function update(Request $request, Subscriber $subscriber)
    {
         $data = $request->validate([
            'fio' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'login' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'ip' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'band' => 'nullable|string|max:255',
            'cabinet1' => 'nullable|string|max:255',
            'cabinet2' => 'nullable|string|max:255',
            'switch_address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:255',
            'active' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'date' => 'nullable|string',
        ]);

        $subscriber->update($data);

        return response()->json($subscriber);
    }

    // Удаляет одного абонента
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return response()->json(['success' => true]);
    }

    // Массовое удаление абонентов
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // массив ID из чекбоксов

        if (!$ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'Выберите хотя бы одного абонента для удаления.');
        }

        Subscriber::whereIn('id', $ids)->delete();

        return redirect()->route('subscribers.index')->with('success', 'Выбранные абоненты удалены.');
    }
    
    public function data(Request $request)
    {
        $query = Subscriber::select([
            'id',
            'fio',
            'city',
            'address',
            'service',
            'login',
            'number',
            'ip',
            'password',
            'band',
            'cabinet1',
            'cabinet2',
            'switch_address',
            'port',
            'active',
            'note',
            'date'
        ]);

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {

                // === Поиск по умной кнопке фильтра (наш JSON filter) ===
                if ($request->has('filter')) {
                    foreach ($request->filter as $field => $value) {
                        if (!empty($value)) {
                            $query->where($field, 'like', "%{$value}%");
                        }
                    }
                }

                // === Поиск по конкретным колонкам DataTables ===
                if ($request->has('columns')) {
                    foreach ($request->columns as $column) {
                        $columnName = $column['data'] ?? null;
                        $searchValue = $column['search']['value'] ?? null;
                        $columnControlValue = $column['columnControl']['search']['value'] ?? null;

                        $value = $columnControlValue ?: $searchValue;

                        if (!empty($value) && !empty($columnName)) {
                            $query->where($columnName, 'like', "%{$value}%");
                        }
                    }
                }

                // === Глобальный поиск ===
                $Searchvalue = $request->input('search.value');

                if (!empty($Searchvalue)) {
                    $query->where(function ($q) use ($Searchvalue, $request) {
                        foreach ($request->columns as $column) {
                            $columnName = $column['data'] ?? null;
                            if (!empty($columnName)) {
                                $q->orWhere($columnName, 'like', "%{$Searchvalue}%");
                            }
                        }
                    });
                }

            })
            ->make(true);
    }
    
    

    public function export(Request $request)
    {
    // Берём все фильтры как массив
    $filters = $request->all();

    return Excel::download(new SubscribersExport($filters), 'subscribers.xlsx');

    }
}
