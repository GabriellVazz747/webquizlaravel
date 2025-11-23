<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StartQuizRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return []; }
}
