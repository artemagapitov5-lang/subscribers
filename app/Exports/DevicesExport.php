<?php

namespace App\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\FromCollection;

class DevicesExport implements FromCollection
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Device::query();

        // Если пришёл список ID — экспортируем только их (точно как в отфильтрованной таблице)
        if (!empty($this->filters['ids'])) {
            $ids = is_array($this->filters['ids'])
                ? $this->filters['ids']
                : explode(',', $this->filters['ids']);

            $ids = array_filter($ids, fn ($id) => trim($id) !== '');

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }
        // Иначе используем глобальный поиск (запасной вариант)
        elseif (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('equipment', 'like', "%$search%")
                  ->orWhere('serial', 'like', "%$search%")
                  ->orWhere('inventory', 'like', "%$search%")
                  ->orWhere('sity', 'like', "%$search%")
                  ->orWhere('install', 'like', "%$search%")
                  ->orWhere('date', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhere('FIO', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('manager', 'like', "%$search%");
            });
        }

        return $query->get();
    }
}

