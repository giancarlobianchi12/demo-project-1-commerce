<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $isStoring = $this->routeIs('admin.users.store');
        $fieldPresent = $isStoring ? 'required' : 'sometimes';

        $data = [
            'name' => "$fieldPresent|string|min:3|max:255",
            'email' => "$fieldPresent|email|unique:users,email,".$this->route('user')?->id,
            'password' => "$fieldPresent|string|min:3|max:255",
        ];

        return $data;
    }

    protected function prepareForValidation()
    {
        if ($this->has('password')) {
            $this->merge([
                'password' => Hash::make($this->password),
            ]);
        }
    }
}
