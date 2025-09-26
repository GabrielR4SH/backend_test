<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRedirectRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autorização pode ser ajustada conforme necessário
    }

    public function rules()
    {
        return [
            'destination_url' => 'nullable|url|https',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        // Se $key for nulo, processa todo o array; caso contrário, retorna o valor específico
        if ($key === null) {
            if (array_key_exists('is_active', $data)) {
                $data['is_active'] = (bool)$data['is_active'];
            }
            return $data;
        }
        return $data[$key] ?? $default;
    }
}
