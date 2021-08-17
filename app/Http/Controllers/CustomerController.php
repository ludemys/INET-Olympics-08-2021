<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class CustomerController extends Controller implements ValidationInterface
{
    public function __construct()
    {
        $this->setModelName(Customer::class);

        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        self::validateIndividually($request);

        $model = $this->getModelName();
        $customer = new $model([
            'number' => $request['number'],
            'full_name' => $request['full_name'],
            'address' => $request['address'],
            'phone_number' => $request['phone_number'],
            'profession' => $request['profession'],
        ]);

        if ($request['is_up_to_date'] && isset($request['is_up_to_date']) && $request['is_up_to_date'] !== null)
        {
            $customer->is_up_to_date = $request['is_up_to_date'];
        }

        try
        {
            $customer->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($customer), 201);
    }

    public static function validateIndividually(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:10|regex:[^0-9]|unique:customer,number',
            'full_name' => 'required|string|max:50|regex:[^a-zA-Z ]',
            'address' => 'required|string|max:255|regex:[^a-zA-Z0-9 ]',
            'phone_number' => 'required|string|max:15|regex:[^0-9\-]',
            'profession' => 'required|string|max:255|regex:[^a-zA-Z0-9 ]',
            'is_up_to_date' => 'nullable|boolean',
        ]);

        $request['number'] = self::addZerosToLeft($request['number'], 10);
    }
}
