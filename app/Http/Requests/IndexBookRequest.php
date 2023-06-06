<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexBookRequest extends FormRequest
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
            'title' => 'string|max:64',
            'author_name' => 'string|max:64',
            'author_id' => 'integer',
            'description' => 'string|max:255'
        ];
    }

    public function getLimit(): int
    {
        return $this->integer('limit', 5);
    }

    public function getOffset(): int
    {
        return $this->integer('offset', 1);
    }

    public function getFilters(): array
    {
        return $this->only(['title', 'author_name', 'author_id', 'description']);
    }
}
