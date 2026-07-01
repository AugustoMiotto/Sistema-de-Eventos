<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Nossos novos campos:
            'is_promoter' => ['boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Aceita imagens até 2MB
        ];
    }
    public function messages(): array
    {
        return [
            'photo.max' => 'A foto de perfil é muito pesada. O tamanho máximo permitido é de 2MB.',
            'photo.image' => 'O arquivo enviado não é válido. Por favor, envie uma imagem.',
            'photo.mimes' => 'A foto precisa ser no formato JPEG, PNG, JPG ou GIF.',
        ];
    }
}
