<?php

namespace App\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;

class GetActivitiesRequest extends FormRequest
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
            'per_page' => ['numeric'],
            'order_by' => ['string', 'in:id,name,order,created_at'],
            'order_direction' => ['string', 'in:asc,desc'],
            'search' => ['string'],
            'not_paginated' => ['boolean'],
            'log_name' => ['nullable', 'string'],
            'subject_id' => ['nullable', 'numeric']
        ];
    }
}
