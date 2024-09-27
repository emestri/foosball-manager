<?php

namespace App\Http\Requests\Set;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'home_forwarder_id' => 'required|integer|exists:users,id',
            'guest_forwarder_id' => 'required|integer|exists:users,id',
            'home_goals' => 'required|integer',
            'guest_goals' => 'required|integer',
        ];
    }
}
