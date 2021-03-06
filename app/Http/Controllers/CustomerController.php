<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Customer;
use App\Models\Roomclass;
use App\Models\RoomclassCustomer;
use Dflydev\DotAccessData\Exception\DataException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use Throwable;

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
        else
        {
            $customer->is_up_to_date = false;
        }

        try
        {
            $customer->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        if ($request->has('classes_json'))
        {
            $customer_id = DB::table('customers')
                ->select(['id'])
                ->orderByDesc('id')
                ->offset(1)
                ->first();

            $classes_json = $request->input('classes_json');
            $roomclass_controller = new RoomclassController();

            foreach ($classes_json as $class_id)
            {
                try
                {
                    $roomclass_controller->checkIfModelExists($class_id);

                    $roomclass_customer = new RoomclassCustomer([
                        'customer_id' => $customer_id->id,
                        'roomclass_id' => $class_id,
                    ]);

                    $roomclass_customer->save();
                }
                catch (Throwable $error)
                {
                    if ($error instanceof DataException)
                    {
                        return self::throw($error, 404);
                    }
                    return self::throw($error);
                }
            }
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

    public static function validateIndividually(Request $request, bool $invoker_is_store_method = false)
    {
        if ($invoker_is_store_method)
        {
            $validator = Validator::make($request->all(), [
                'number' => 'required|string|digits_between:1,10|unique:customers',
                'full_name' => 'required|string|max:50|regex:(^[a-zA-Z ])',
                'address' => 'required|string|max:255|regex:(^[a-zA-Z0-9 ])',
                'phone_number' => 'required|string|max:15|regex:(^[0-9\-+ ])',
                'profession' => 'required|string|max:255|regex:(^[a-zA-Z ])',
                'is_up_to_date' => 'nullable|boolean',
                'classes_json' => 'nullable|json'
            ]);

            if ($request->has('classes_json'))
            {
                $classes_json = json_decode($request->input('classes_json'));

                foreach ($classes_json as $class_id)
                {
                    Validator::make(
                        ['class_id' => $class_id],
                        [
                            'class_id' => 'numeric|digits_between:1,255|regex:(^[0-9])'
                        ]
                    );
                }
            }
        }

        $validator = Validator::make($request->all(), [
            'number' => 'required|string|digits_between:1,10|unique:customers',
            'full_name' => 'required|string|max:50|regex:(^[a-zA-Z ])',
            'address' => 'required|string|max:255|regex:(^[a-zA-Z0-9 ])',
            'phone_number' => 'required|string|max:15|regex:(^[0-9\-+ ])',
            'profession' => 'required|string|max:255|regex:(^[a-zA-Z ])',
            'is_up_to_date' => 'nullable|boolean',
        ]);

        $request['number'] = self::addZerosToLeft($request['number'], 10);
    }
}
