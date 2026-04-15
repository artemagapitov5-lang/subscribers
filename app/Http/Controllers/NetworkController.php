<?php

namespace App\Http\Controllers;

use App\Models\Network;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NetworkController extends Controller
{
    /**
     * Данные для DataTables во вкладке Network.
     */
    public function data(Request $request)
    {
        $query = Network::select([
            'id',
            'ip',
            'equipment',
            'location',
            'note',
            'vlan',
            'subnet',
            'status',
        ]);

        return DataTables::of($query)->make(true);
    }

    /**
     * Сохранение новой записи сети.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ip'        => ['required', 'string', 'max:255'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'location'  => ['nullable', 'string', 'max:255'],
            'note'      => ['nullable', 'string', 'max:255'],
            'vlan'      => ['nullable', 'integer', 'max:4094'],
            'subnet'    => ['nullable', 'string', 'max:255'],
            'status'    => ['nullable', 'string', 'max:50'],
        ]);

        $network = Network::create($data);

        return response()->json($network, 201);
    }

    /**
     * Обновление существующей записи сети.
     */
    public function update(Request $request, Network $network)
    {
        $data = $request->validate([
            'ip'        => ['sometimes', 'required', 'string', 'max:255'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'location'  => ['nullable', 'string', 'max:255'],
            'note'      => ['nullable', 'string', 'max:255'],
            'vlan'      => ['nullable', 'integer', 'max:4094'],
            'subnet'    => ['nullable', 'string', 'max:255'],
            'status'    => ['nullable', 'string', 'max:50'],
        ]);

        $network->update($data);

        return response()->json($network);
    }

    /**
     * Удаление записи сети.
     */
    public function destroy(Network $network)
    {
        $network->delete();

        return response()->json(['success' => true]);
    }
}
