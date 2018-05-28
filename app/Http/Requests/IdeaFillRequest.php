<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdeaFillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article_format_type' => 'required',
            'link_to_model_article' => 'required',
            'references' => 'required',
            'points_covered' => 'required',
            'points_avoid' => 'required',
            'additional_notes' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'article_format_type.required' => 'This field is required',
            'link_to_model_article.required' => 'This field is required',
            'references.required' => 'This field is required',
            'points_covered.required' => 'This field is required',
            'points_avoid.required' => 'This field is required',
            'additional_notes.required' => 'This field is required',
        ];
    }
}
