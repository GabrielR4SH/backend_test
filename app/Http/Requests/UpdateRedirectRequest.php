<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRedirectRequest extends FormRequest
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
    public function rules(): array
    {
        $rules = [];
        if ($this->has('destination_url')) {
            $rules['destination_url'] = (new StoreRedirectRequest())->rules()['destination_url'];  // mesmas validações de store
        }
        $rules['is_active'] = 'boolean';
        return $rules;
    }
}
