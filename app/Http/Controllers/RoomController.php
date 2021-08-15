<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class RoomController extends Controller
{

    public function __construct()
    {
        $this->setModelName(Room::class);
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
        $request->validate([
            'number' => 'required|digits:3|max:3',
            'size' => 'required|max:8',
            'location' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @throws Throwable
     */
    public function update(Request $request, $id)
    {
        // Verifies if the register exists
        try
        {
            $this->getModelName()::findOrFail($id);
        }
        catch (\Throwable $error)
        {
            return self::throw($error, 404);
        }

        if (!$request->has('number'))
        {
            $request['number'] = null;
        }
        if (!$request->has('size'))
        {
            $request['size'] = null;
        }
        if (!$request->has('location'))
        {
            $request['location'] = null;
        }
        if (!$request->has('type'))
        {
            $request['type'] = null;
        }

        $request->validate([
            'number' => 'nullable|digits:3|max:3',
            'size' => 'nullable|max:8',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        try
        {
            $updated_room = array();
            foreach ($request->all() as $key => $value)
            {
                if ($value !== null)
                {
                    $updated_room[$key] = $value;
                }
            }

            $this->getModelName()::query()
                ->where('id', '=', $id)
                ->update($updated_room);
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(
            json_encode($this->getModelName()::find($id)),
            200
        );
    }
}
