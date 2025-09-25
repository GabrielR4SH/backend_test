<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;

class StoreRedirectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Sem authenticação
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'destination_url' => [  // Validações compostas
                'required', 'url',  // URL válida
                Rule::startsWith('https://'),  // Deve ser HTTPS
                function ($attribute, $value, $fail) {  // Não aponta para app
                    if (str_contains($value, request()->getHost())) {
                        $fail('URL cannot point to the application itself.');
                    }
                },
                function ($attribute, $value, $fail) {  // Status 200/201
                    try {
                        $client = new Client(['verify' => false]);  // Ignora SSL para testes
                        $response = $client->head($value);
                        if (!in_array($response->getStatusCode(), [200, 201])) {
                            $fail('URL must return 200 or 201 status.');
                        }
                    } catch (\Exception $e) {
                        $fail('Invalid URL: ' . $e->getMessage());  // DNS inválido, etc.
                    }
                },
            ],
        ];
    }
}
