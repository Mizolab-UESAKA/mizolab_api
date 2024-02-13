<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait SetCreatedByAndUpdatedBy
{
    public static function bootSetCreatedByAndUpdatedBy()
    {
        static::saving(function ($model) {
            $current_admin = Auth::guard('admin_api')->user();

            if ($current_admin) {
                if (!$model->created_by) {
                    $model->created_by = $current_admin->id;
                }
                $model->updated_by = $current_admin->id;
            }
        });
    }
}
