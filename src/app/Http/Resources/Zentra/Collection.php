<?php

namespace App\Http\Resources\Zentra;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->resource = $this->collectResource($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $zentra_data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return array_reduce($zentra_data, function (array $acc, array $el){
            $group = $el['value_type_id'];
            if (!isset($acc[$group])) {
                $acc[$group] = [
                    'place_name' => $el['place_name'],
                    'device_sn' => $el['device_sn'],
                    'data_type' => $el['data_type'],
                    'port_number' => $el['port_number'],
                    'sensor_name' => $el['sensor_name'],
                    'units' => $el['units'],
                ];  // 最初のアイテムを追加
            }
            $acc[$group]['data'][] = ['time' => $el['time'], 'value' => $el['value']];
            return $acc;
        }, []);
    }
}
