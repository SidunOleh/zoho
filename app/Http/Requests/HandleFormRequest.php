<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandleFormRequest extends FormRequest
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
        $stages = [
            'Qualification',
            'Needs Analysis',
            'Value Proposition',
            'Identify Decision Makers',
            'Proposal/Price Quote',
            'Negotiation/Review',
            'Closed Won',
            'Closed Lost',
            'Closed-Lost to Competition',
        ];

        return [
            'account.Account_Name' => 'required|string',
            'account.Website' => 'required|active_url',
            'account.Phone' => 'required|regex:/^\(\d{3}\) \d{3}\-\d{4}$/',
            'deal.Deal_Name' => 'required|string',
            'deal.Stage' => 'required|in:' . implode(',', $stages),
        ];
    }
}
