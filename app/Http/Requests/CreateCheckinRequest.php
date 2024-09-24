<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'checkin' => 'required',
            // 'location' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
            'meter' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Tên nhân viên không được để trống',
            'checkin.required' => 'Checkin không được để trống',
            // 'location.required' => 'Vị trí không được để trống',
            'latitude.required' => 'Vĩ độ không được để trống',
            'longtitude.required' => 'Kinh độ không được để trống',
            'meter.required' => 'Khoảng cách không được để trống',
        ];
    }
}
