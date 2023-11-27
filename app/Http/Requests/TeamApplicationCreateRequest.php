<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TeamApplicationCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $user = \Auth::user();
        $this->merge([
            'ID_user' => $user->ID_user,
            'approval_status' => 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'ID_user' => [
                'required',
                'exists:user,ID_user',
                function ($attribute, $value, $fail) {
                    $user = \Auth::user();
                    $teamManagerApplication = \App\Models\TeamManagerApplication::where('ID_user', $user->ID_user)->where('approval_status', 0)->first();
                    if ($teamManagerApplication) {
                        $fail(__('messages.response_team_manager_application_exists'));
                    }
                },
            ],
            'is_agree_with_tnc' => 'required|boolean',
            'tnc_version' => 'required',
            'person_in_charge_status' => 'required',
            'user_manage_status' => 'required',
            'approval_status' => 'required|in:1,0',
            'notes' => 'nullable|min:5|max:300',
        ];
    
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'is_agree_with_tnc.required' => __('messages.response_you_must_be_agree_with_tnc'),
            'person_in_charge_status.required' => ':attribute ,' . __('messages.response_this_is_required'),
            'user_manage_status.required' => ':attribute ,' . __('messages.response_this_is_required')
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'Validation failed',
                'messages' => $errors,
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
