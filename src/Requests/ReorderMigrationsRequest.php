<?php

namespace Nerbiz\ReorderMigrations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderMigrationsRequest extends FormRequest
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
            'naming_mode' => ['bail', 'required', 'regex:/^current|custom$/'],
            'filename_prefix' => 'bail|required_if:naming_mode,custom',
            'filenames' => 'bail|required|array',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function messages(): array
    {
        return [
            'naming_mode.*' => __('Choose a valid naming mode'),
            'filename_prefix' => __('Fill in the custom prefix'),
            'filenames' => __('The list of filenames is invalid'),
        ];
    }
}
