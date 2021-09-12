<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title'=>'required|min:2|max:100',
            'slug'=>'required|unique:posts,slug',
            'desc'=>'required|min:2|max:500',
            'category_id'=>'required|exists:categories,id'.$this->category,
            'tags.*'      =>'required|exists:tags,id',

            'images.*'=>'nullable|mimes:jpg,jpeg,png,bmp|max:2000',
            'attachment.*'=>'nullable',
            'gallery.*'=>'nullable|exists:galleries,id',

        ];
    }
    protected function prepareForValidation() 
    {
        $this->merge(['slug' => $this->slug = str_replace(' ', '_', $this->slug)]) ;
        
    } 
}
