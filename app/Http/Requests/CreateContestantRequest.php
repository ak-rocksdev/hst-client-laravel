<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Event;
use App\Models\Competition;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Lang;

class CreateContestantRequest extends FormRequest
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
        FacadesValidator::extend('valid_event_id', function ($attribute, $value, $parameters, $validator) {
            return Event::where('ID_event', $value)
                        ->where('registration_type', 'open')
                        // ->where('start_registration', '<=', now())
                        // ->where('end_registration', '>=', now())
                        ->exists();
        }, __('messages.response_event_date_invalid'));

        FacadesValidator::extend('user_id_not_registered', function ($attribute, $value, $parameters, $validator) {
            $event = Event::where('ID_event', $validator->getData()['ID_event'])->first();
            $competitions = Competition::where('ID_event', $event->ID_event)
                ->leftJoin('contestant_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                ->where('contestant_list.ID_user', $value)
                ->select('contestant_list.ID_user', 'competition_list.ID_competition')
                ->get();
            foreach($competitions as $competition) {
                if(in_array($competition->ID_competition, $validator->getData()['ID_competition'])) {
                    return false;
                }
            }
            return true;
        }, __('messages.response_competition_already_registered'));

        // check if user ID is not register more than max_join_competition in event_list
        FacadesValidator::extend('max_join_competition', function ($attribute, $value, $parameters, $validator) {
            $event = Event::where('ID_event', $validator->getData()['ID_event'])->first();
            $competitions = Competition::where('ID_event', $event->ID_event)
                ->leftJoin('contestant_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                ->where('contestant_list.ID_user', $value)
                ->select('contestant_list.ID_user', 'competition_list.ID_competition')
                ->get();
            if($competitions->count() + count($validator->getData()['ID_competition']) > $event->max_join_competition) {
                $message = Lang::get('messages.response_max_join_competition', [
                    'number1' => $event->max_join_competition,
                    'number2' => count($validator->getData()['ID_competition'])
                ]);
                $validator->setCustomMessages(['max_join_competition' => $message]);
                return false;
            }
            return true;
        });
        return [
            'ID_event' => [
                'required',
                'exists:event_list,ID_event',
                'valid_event_id',
            ],
            'ID_competition.*' => 'required|exists:competition_list,ID_competition',
            'ID_user' => 'required|exists:user,ID_user|user_id_not_registered|max_join_competition',
        ];
    }

    public function messages(): array
    {
        return [
            'ID_event.valid_event_id' => __('messages.response_event_date_invalid')
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'Validation failed',
                'messages' => $errors,
                'code' => 422
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
