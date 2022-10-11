<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionFormRequest extends FormRequest
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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'delivery_from_time' => 'from time',
            'delivery_to_time' => 'to time',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'delivery_to_time.after:delivery_from_time' => 'The to time must be a time after from time',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'delivery_address' => 'required|max:255',
            'delivery_from_time' => 'required',
            'delivery_to_time' => 'required|after:delivery_from_time',
            'note' => 'max:255',
        ];

        $imageValidation = [
            'image_1' => 'required|mimes:png,jpg,jpeg',
            'image_2' => 'mimes:png,jpg,jpeg',
            'image_3' => 'mimes:png,jpg,jpeg',
            'image_4' => 'mimes:png,jpg,jpeg',
            'image_5' => 'mimes:png,jpg,jpeg',
        ];

        return array_merge($rules, $imageValidation);
    }
}
