<?php
namespace App\Http\Validation;
use App\Http\Interfaces\ValidationInterface;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class NoteStoreValidation 
{
    protected $rules;
    public function __construct()
    {

        $this->rules = [
            'data'=> [
                'required',
                'array',
            ],
            'data.attributes'=> [
                'required',
            'array',
            ],
            'data.attributes.content'=> [
                'required',
                'string',
            ],
        ];
        
    }
    
    /**
     * make validation with validator and $this->rules
     * 
     * param  \Illuminate\Http\Request  $request
     * return MessageBag Laravel || false 
     */
    public function execValidation(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return false;
    }
}
