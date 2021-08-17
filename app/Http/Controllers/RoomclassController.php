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

class RoomclassController extends Controller implements ValidationInterface
{
    public function __construct()
    {
        $this->setModelName(Roomclass::class);

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
        $roomclass = new $model([
            'number' => $request['number'],
            'size' => $request['description'],
            'price' => $request['price'],
            'days_combination_id' => $request['days_combination_id'],
            'room_id' => $request['room_id'],
            'professor_id' => $request['professor_id'],
        ]);

        try
        {
            $roomclass->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($roomclass), 201);
    }

    /**
     * List all of the students that assist to the given roomclass
     * @param int $id
     * 
     * @return Response
     * @throws DataException|ServerException
     */
    public function listStudents(int $id)
    {
        // Verifies if user with id = $id exists using this very class show method
        try
        {
            if (!$this->getModelName()::findOrFail($id))
            {
                throw new DataException("Class with id = $id not found", 404);
            }
        }
        catch (DataException $error)
        {
            return self::throw($error, 404);
        }

        // Asks for the students and throws an Exception if the query fails
        try
        {
            $roomclass_customer = new RoomclassCustomer();
            $students_ids = $roomclass_customer->query()
                ->where('roomclass_id', '=', $id)
                ->get('customer_id');

            if (!isset($students_ids) || !$students_ids)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            self::throw($error);
        }

        // Returns OK if there is no registers on $students_id
        if (count($students_ids) < 1)
        {
            return new Response(json_encode($students_ids), 200);
        }

        $students = array();
        $customer = new Customer();

        foreach ($students_ids as $student)
        {
            array_push(
                $students,
                $customer->query()
                    ->where('id', '=', $student->customer_id)
                    ->first()
            );
        }

        return new Response(json_encode($students), 200);
    }

    public static function validateIndividually(Request $request)
    {
        $request->validate([
            'number' => 'required|digits:4|max:4|unique:roomclasses,number',
            'description' => 'required|string|max:500',
            'price' => 'required',
            'days_combination_id' => 'required|numeric',
            'room_id' => 'required|numeric',
            'professor_id' => 'required|numeric',
        ]);

        $request['number'] = self::addZerosToLeft($request['number'], 4);
    }
}
