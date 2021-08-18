<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Customer;
use App\Models\Roomclass;
use App\Models\RoomclassCustomer;
use Exception;
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

    /**
     * Returns all the classes for a given customer
     * @param int $id
     * 
     * @return Response
     * @throws Exception
     */
    public function getAllClasses(int $id)
    {
        $this->checkIfModelExists($id);

        // Asks for the roomclasses and throws an Exception if the query fails
        try
        {
            $roomclass_customer = new RoomclassCustomer();
            $roomclasses_ids = $roomclass_customer->query()
                ->where('customer_id', '=', $id)
                ->get('roomclass_id');

            if (!isset($roomclasses_ids) || !$roomclasses_ids)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            return self::throw($error);
        }

        // Returns OK if there is no registers on $students_id
        if (count($roomclasses_ids) < 1)
        {
            return new Response(json_encode($roomclasses_ids), 200);
        }

        $roomclasses = array();

        foreach ($roomclasses_ids as $roomclass)
        {
            array_push(
                $roomclasses,
                Roomclass::query()
                    ->where('id', '=', $roomclass->roomclass_id)
                    ->first()
            );
        }

        return new Response(json_encode($roomclasses), 200);
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
