<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DevicesExport;

class DeviceController extends Controller
{
    // Список устройств с фильтрацией
    public function index(Request $request)
    {
        // Страница списка только отдаёт шаблон,
        // сами данные для таблицы подгружаются порциями через маршрут equipment.data
        return view('equipment.index');
    }

    // Создать новое устройство
    public function create()
    {
        return view('devices.create');
    }

    // Сохранить новое устройство
    public function store(Request $request)
    {
        $data = $request->validate([
            'equipment' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'inventory' => 'nullable|string|max:255',
            'sity' => 'nullable|string|max:255',
            'install' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'FIO' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'segment' => 'nullable|string|max:255',
            'dealer' => 'nullable|string|max:255',
        ]);

        $device = \App\Models\Device::create($data);

        return response()->json($device);
    }

    // Редактирование устройства
    public function edit($device)
    {
        // Поддержка обоих маршрутов: devices/{device} и equipment/{equipment}
        // Route model binding автоматически преобразует оба параметра в модель Device
        if (!$device instanceof Device) {
            $device = Device::findOrFail($device);
        }
        return view('devices.edit', compact('device'));
    }

    // Обновить данные устройства
    public function update(Request $request, $device)
    {
        // Поддержка обоих маршрутов: devices/{device} и equipment/{equipment}
        if (!$device instanceof Device) {
            $device = Device::findOrFail($device);
        }
        
        $data = $request->validate([
            'equipment' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'inventory' => 'nullable|string|max:255',
            'sity' => 'nullable|string|max:255',
            'install' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'FIO' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'segment' => 'nullable|string|max:255',
            'dealer' => 'nullable|string|max:255',
        ]);

        $device->update($data);

        return response()->json($device);
    }

    // Удаление устройства
    public function destroy($device)
    {
        // Поддержка обоих маршрутов: devices/{device} и equipment/{equipment}
        if (!$device instanceof Device) {
            $device = Device::findOrFail($device);
        }
        $device->delete();
        return response()->json(['success' => true]);
    }

    // Массовое удаление
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'Выберите хотя бы одно устройство для удаления.');
        }

        Device::whereIn('id', $ids)->delete();

        return redirect()->route('devices.index')->with('success', 'Выбранные устройства удалены.');
    }

    public function data(Request $request)
    {
        $query = Device::select([
            'id',
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
            'dealer'
        ]);
    
        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                // === Поиск по конкретным колонкам ===
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

        return Excel::download(new DevicesExport($filters), 'equipment.xlsx');
    }
}
