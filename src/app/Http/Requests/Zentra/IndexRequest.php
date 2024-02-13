<?php

namespace App\Http\Requests\Zentra;

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
            'value_type_id_list' => 'nullable|array',
            'value_type_id_list.*' => 'nullable|int', // 配列内の各要素が整数であることを検証
        ];
    }
}
