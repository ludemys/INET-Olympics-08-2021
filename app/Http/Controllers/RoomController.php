<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\Room;
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
            'number' => 'required|digits:3|max:3|unique:rooms,number',
            'size' => 'required|max:8',
            'location' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $request['number'] = self::addZerosToLeft($request['number'], 3);
    }
}
