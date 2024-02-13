<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SetCreatedByAndUpdatedBy;

class DataEntities extends Model
{
    use HasFactory, SetCreatedByAndUpdatedBy;

    protected $table = "data_entities";
    protected $fillable = [
        'place_id',
        'device_sn',
        'data_type',
        'port_number',
        'sensor_name',
        'units',
        'id',
    ];
    const ACTIVE = 1;
    const INACTIVE = 0;
}
