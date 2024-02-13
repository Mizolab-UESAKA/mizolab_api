<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SetCreatedByAndUpdatedBy;

class Zentra extends Model
{
    use HasFactory, SetCreatedByAndUpdatedBy;

    protected $table = "zentra";
    protected $fillable = [
        'value_type_id',
        'value',
    ];
    const ACTIVE = 1;
    const INACTIVE = 0;
}
