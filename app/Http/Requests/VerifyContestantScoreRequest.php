<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use Illuminate\Validation\Rule;

use App\Rules\CheckAllJudgesHasBeenSubmittedRule;

class VerifyContestantScoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ID_contestant' => 'required',
            'ID_games' => [
                'required',
                new CheckAllJudgesHasBeenSubmittedRule($this->ID_contestant, $this->ID_games),
                Rule::unique('score_list')
                    ->where('ID_contestant', $this->ID_contestant)
                    ->where('ID_games', $this->ID_games)
                    ->where('ID_judge', 0)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'ID_games.unique' => __('messages.response_score_is_already_verified')
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
