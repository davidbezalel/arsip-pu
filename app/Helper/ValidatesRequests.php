<?php

namespace App\Helper;

use Illuminate\Foundation\Validation\ValidatesRequests as BaseValidateRequests;
use Illuminate\Http\Request;


trait ValidatesRequests
{

    use BaseValidateRequests;

    public function validate_v2(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            return $validator->getMessageBag()->first();
        }
    }

}