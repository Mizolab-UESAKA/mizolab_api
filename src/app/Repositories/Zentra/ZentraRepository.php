<?php

namespace App\Repositories\Zentra;

use App\Repositories\BaseRepository;
use App\Models\Zentra;
use Illuminate\Support\Facades\DB;

class ZentraRepository extends BaseRepository {

    /**
     * Get requests.
     *
     * @param  App\Http\Requests\Admin\Zentra\????????? $request
     * @return App\Models\ $data_entities
     */
    public static function getZentra($request)
    {
        $value_type_id_list = $request['value_type_id_list'] ?? null;

        $zentra_data = Zentra::join('data_entities', 'zentra.value_type_id', 'data_entities.id')
            ->join('place', 'data_entities.place_id', 'place.id')
            ->select(
                'place.place_name',
                'zentra.value',
                'zentra.time',
                'zentra.value_type_id',
                'data_entities.device_sn',
                'data_entities.data_type',
                'data_entities.port_number',
                'data_entities.sensor_name',
                'data_entities.units',
            )
            ->whereIn('zentra.value_type_id', $value_type_id_list)
            ->get();

        return $zentra_data;
    }
}
