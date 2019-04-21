<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class QuestionResultRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'question_id' => 'required|exists:questions,id',
                    'answers' => 'required|array|distinct'
                ];
                break;
        }
    }
}
