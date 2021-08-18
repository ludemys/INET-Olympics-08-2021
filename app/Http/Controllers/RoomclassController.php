<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Roomclass;
use App\Models\RoomclassCustomer;
use Dflydev\DotAccessData\Exception\DataException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        $students = $this->getStudentsById($id);

        return new Response(json_encode($students), 200);
    }

    /**
     * For this task, I interpreted the type of the room that hosts the class as the class "name". This method group all the classes by this "name".
     * @return Response
     * @throws Exception
     */
    public function only()
    {
        $roomclasses = Room::query()
            ->get(['id', 'type']);

        try
        {
            if ($roomclasses === null)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            self::throw($error);
        }

        return new Response(json_encode($roomclasses), 200);
    }

    /**
     * Returns all the debt-ridden students in a given class by id or room type.
     * @param string $criteria
     * @param int|string $value
     * @param bool $byType
     * @param string $modifier
     * 
     * @return Response
     * @throws DataException|Exception
     */
    public function getStudentsByCriteria($value, string $criteria = 'id', bool $byType = false, string $modifier = null)
    {
        switch ($criteria)
        {
            case 'type':
                RoomController::checkIfRoomExistsByType($value);
                $rooms = RoomController::getRoomsByType($value);


                if (count($rooms) == 1)
                {
                    $roomclasses = Roomclass::query()
                        ->where('room_id', '=', $rooms[0]->id)
                        ->get();
                }
                else
                {
                    $roomclasses = [];

                    foreach ($rooms as $room)
                    {
                        $roomclasses_array = Roomclass::query()
                            ->where('room_id', '=', $room->id)
                            ->get();

                        if ($roomclasses_array)
                        {
                            $roomclasses += $roomclasses_array->toArray();
                        }
                    }
                }

                break;
            default: // Case 'id':
                $value = (int)$value;
                $this->checkIfModelExists($value);

                if (!$byType)
                {
                    return new Response(json_encode($this->getStudentsById($value, 'debtor')), 200);
                }

                $type = Roomclass::query()
                    ->where('id', '=', $value)->first()
                    ->room->type;

                $roomclasses = DB::table('roomclasses')
                    ->join('rooms', 'roomclasses.room_id', '=', 'rooms.id')
                    ->where('rooms.type', '=', $type)
                    ->select('roomclasses.id')
                    ->get();

                break;
        }
        $students = [];

        foreach ($roomclasses as $roomclass)
        {
            if ($modifier !== null && $modifier === 'debtor')
            {
                $students = array_merge(
                    $students,
                    (array)$this->getStudentsById($roomclass['id'], 'debtor')
                );

                continue;
            }

            return var_dump($roomclass);
            return var_dump($this->getStudentsById($roomclass['id']));
            $students = array_merge(
                $students,
                $this->getStudentsById($roomclass['id'])
            );
        }

        try
        {
            if ($students === null)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($students), 200);
    }

    // public function get

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

    /**
     * Reusable method for getting all the students in a given roomclass.
     * 
     * @param int $id
     * 
     * @return array
     * @throws DataException|Exception
     */
    public function getStudentsById(int $id, string $modifier = null)
    {
        ($this->checkIfModelExists($id));

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
            return self::throw($error);
        }

        // Returns OK if there is no registers on $students_id
        if (count($students_ids) < 1)
        {
            return $students_ids;
        }

        $students = array();
        $customer = new Customer();

        foreach ($students_ids as $student)
        {
            if ($modifier !== null && $modifier == 'debtor')
            {
                array_push(
                    $students,
                    $customer->query()
                        ->where('id', '=', $student->customer_id)
                        ->where('is_up_to_date', '=', false)
                        ->first()
                );
                continue;
            }

            array_push(
                $students,
                $customer->query()
                    ->where('id', '=', $student->customer_id)
                    ->first()
            );
        }
        return $students;
    }
}
