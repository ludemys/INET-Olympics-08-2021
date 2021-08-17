<?php

namespace App\Helpers;

use Illuminate\Http\Request;

interface ValidationInterface
{
    static function validateIndividually(Request $request);
}
