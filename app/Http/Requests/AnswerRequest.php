<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'question_id' => 'required|integer|exists:questions,id',
            'alternative_id' => 'required|integer|exists:alternatives,id',
            'time_taken' => 'nullable|integer|min:0'
        ];
    }
}
