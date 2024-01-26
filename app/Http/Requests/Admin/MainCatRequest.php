<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MainCatRequest extends FormRequest
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
            'photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'category' => 'required|array|min:1',
            'category.*.name' => 'required|string|max:100',
            'category.*.abbr' => 'required|max:10|string',
            //'category.*.active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'required_without'=>'هذا الحقل مطلوب',
            'category.*.name.string' => 'اسم اللغة لابد ان يكون احرف',
            'category.*.abbr.max' => 'هذا الحقل لابد الا يزيد عن 10 احرف ',
            'category.*.abbr.string' => 'هذا الحقل لابد ان يكون احرف ',
            'category.*.name.max' => 'اسم اللغة لابد الا يزيد عن 100 احرف ',
        ];
    }
}
