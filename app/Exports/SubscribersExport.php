<?php

namespace App\Exports;

use App\Models\Subscriber;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubscribersExport implements FromCollection
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Subscriber::query();

        // Глобальный поиск DataTables
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('fio', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%")
                  ->orWhere('service', 'like', "%$search%")
                  ->orWhere('login', 'like', "%$search%")
                  ->orWhere('number', 'like', "%$search%")
                  ->orWhere('ip', 'like', "%$search%")
                  ->orWhere('password', 'like', "%$search%")
                  ->orWhere('band', 'like', "%$search%")
                  ->orWhere('cabinet1', 'like', "%$search%")
                  ->orWhere('cabinet2', 'like', "%$search%")
                  ->orWhere('switch_address', 'like', "%$search%")
                  ->orWhere('port', 'like', "%$search%")
                  ->orWhere('active', 'like', "%$search%")
                  ->orWhere('note', 'like', "%$search%")
                  ->orWhere('date', 'like', "%$search%");
            });
        }

        return $query->get();
    }
}
