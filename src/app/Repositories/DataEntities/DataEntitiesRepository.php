<?php

namespace App\Repositories\DataEntities;

use App\Repositories\BaseRepository;
use App\Models\DataEntities;
use Illuminate\Support\Facades\DB;

class DataEntitiesRepository extends BaseRepository {

    /**
     * Get requests.
     *
     * @param  App\Http\Requests\Admin\DataEntities\????????? $request
     * @return App\Models\ $data_entities
     */
    public static function getDataEntities($request)
    {
        $place_id = $request['place_id'] ?? null;
        $device_sn = $request['device_sn'] ?? null;
        $port_number = $request['port_number'] ?? null;
        $order_by = $request['order_by'] ?? null;

        $data_entities = DataEntities::join('place', 'data_entities.place_id', 'place.id')
            ->select(
                'place.place_name',
                'data_entities.place_id',
                'device_sn',
                'data_type',
                'port_number',
                'sensor_name',
                'units',
                'data_entities.id'
            )
            ->when($place_id, function ($query) use ($place_id) {
                return $query->Where('data_entities.place_id', '=', $place_id);
                })
            ->when($device_sn, function ($query) use ($device_sn) {
                return $query->Where('data_entities.device_sn', '=', $device_sn);
                })
            ->when($port_number, function ($query) use ($port_number) {
                return $query->Where('data_entities.port_number', '=', $port_number);
                })
            ->when($order_by, function ($query) use ($order_by) {
                return $query->orderBy($order_by);
                })
            ->get();

        return $data_entities;
    }
}
