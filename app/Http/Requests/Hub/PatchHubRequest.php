<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;

class PatchHubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'departement' => 'string|nullable',
            'code_postal' => 'integer|nullable|min:100|max:99999',
            'adresse' => 'string|nullable',
            'complement_adresse' => 'string|nullable',
            'abonne_freebox' => 'integer|nullable',
            'abonne_mobile' => 'integer|nullable',
        ];
    }
}
