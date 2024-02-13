<?php

namespace App\Http\Requests\DataEntities;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'place_id'     => 'nullable|int|max:10',
            'device_sn'    => 'nullable|string|max:10',
            'port_number'  => 'nullable|int|max:10',
            'order_by'     => 'nullable|string|max:20'
        ];
    }
}
