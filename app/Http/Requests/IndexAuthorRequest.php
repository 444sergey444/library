<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexAuthorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'limit' => 'integer',
            'offset' => 'integer',
        ];
    }

    public function getLimit(): int
    {
        return (int) $this->get('limit', 5);
    }

    public function getOffset(): int
    {
        return (int) $this->get('offset', 1);
    }
}
