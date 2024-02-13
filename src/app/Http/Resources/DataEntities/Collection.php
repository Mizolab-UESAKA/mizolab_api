<?php

namespace App\Http\Resources\DataEntities;

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
        return $this->collection->transform(function ($dataEntitiesIndex) use ($request) {
            return [
            'place_name' => $dataEntitiesIndex->place_name,
            'device_sn' => $dataEntitiesIndex->device_sn,
            'data_type' => $dataEntitiesIndex->data_type,
            'port_number' => $dataEntitiesIndex->port_number,
            'sensor_name' => $dataEntitiesIndex->sensor_name,
            'units' => $dataEntitiesIndex->units,
            'id' => $dataEntitiesIndex->id,
            ];
        })->toArray();
    }
}
