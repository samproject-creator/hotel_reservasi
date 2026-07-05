<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $this->user->id,
            'role'      => 'required|in:admin,petugas',
            'no_hp'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'password'  => 'nullable|min:8|confirmed',
        ];
    }
}
