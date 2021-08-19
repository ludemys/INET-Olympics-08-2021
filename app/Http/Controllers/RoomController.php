<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Room;
use Dflydev\DotAccessData\Exception\DataException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class RoomController extends Controller implements ValidationInterface
{

    public function __construct()
    {
        $this->setModelName(Room::class);

        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @throws Throwable
     */
    public function store(Request $request)
    {

        self::validateIndividually($request);

        $model = $this->getModelName();
        $room = new $model([
            'number' => $request['number'],
            'size' => $request['size'],
            'location' => $request['location'],
            'type' => $request['type'] ? $request['type'] : 'Hall',
        ]);

        try
        {
            $room->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($room), 201);
    }

    public static function validateIndividually(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|max:3|unique:rooms,number',
            'size' => 'required|max:8',
            'location' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $request['number'] = self::addZerosToLeft($request['number'], 3);
    }

    public static function checkIfRoomExistsByType(string $type)
    {
        (bool)$flag = Room::query()
            ->where('type', '=', $type)
            ->first();
        try
        {
            if (!$flag)
            {
                throw new DataException("Room with type = $type not found", 404);
            }
        }
        catch (DataException $error)
        {
            return self::throw($error, 404);
        }
    }
    public static function getRoomsByType(string $type)
    {
        $room = Room::query()
            ->where('type', '=', $type)
            ->get();
        try
        {
            if (!$room)
            {
                throw new DataException("Room with type = $type not found", 404);
            }
        }
        catch (DataException $error)
        {
            return self::throw($error, 404);
        }

        return $room;
    }
}
