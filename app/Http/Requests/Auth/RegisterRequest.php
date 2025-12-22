<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],  // 'lowercase' tidak ada dalam aturan validasi default, dihapus.
            'password' => ['required', 'string', 'min:8'],  // Biasanya tambahkan 'min:8' untuk keamanan kata sandi
            'contact' => ['nullable', 'string'],  // Set ke nullable karena mungkin tidak selalu diisi
            'address' => ['nullable', 'string'],  // Sama dengan 'contact'
            'password_confirmation' => ['required', 'string', 'same:password'],
            // 'ktp' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048']  // Dipisahkan menjadi beberapa aturan yang benar
        ];
    }
}
