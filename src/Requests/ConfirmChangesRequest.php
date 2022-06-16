<?php

namespace Nerbiz\ReorderMigrations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmChangesRequest extends FormRequest
{
    /**
     * {@inheritdoc}
     */
    protected $stopOnFirstFailure = true;

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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_filenames' => 'bail|required|array',
            'new_filenames' => 'bail|required|array',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function messages(): array
    {
        return [
            'current_filenames.*' => __('The list of current filenames is invalid'),
            'new_filenames.*' => __('The list of new filenames is invalid'),
        ];
    }
}
