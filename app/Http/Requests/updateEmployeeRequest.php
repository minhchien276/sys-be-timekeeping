<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateEmployeeRequest extends FormRequest
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
            'image' => 'required',
            'fullname' => 'required',
            'birthday' => 'required',
            'identification' => 'required|numeric',
            'salary' => 'required|numeric',
            'dayOff' => 'required|numeric',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'departmentId' => 'required',
            'roleId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Link ảnh không được để trống',
            'fullname.required' => 'Họ và tên không được để trống',
            'birthday.required' => 'Sinh nhật không được để trống',
            'identification.required' => 'CCCD/CMND không được để trống',
            'identification.numeric' => 'CCCD/CMND không hợp lệ',
            'salary.required' => 'Lương không được để trống',
            'salary.numeric' => 'Lương không hợp lệ',
            'dayOff.required' => 'Nghỉ phép không được để trống',
            'dayOff.numeric' => 'Nghỉ phép không hợp lệ',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'departmentId.required' => 'Phòng ban không được bỏ trống',
            'roleId.required' => 'Chức vụ không hợp lệ',
        ];
    }
}
