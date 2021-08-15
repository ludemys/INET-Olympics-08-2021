<?php

namespace App\Http\Controllers;

use App\Models\DaysCombinations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DaysCombinationController extends Controller
{

    public function __construct()
    {
        $this->setModelName(DaysCombinations::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'days' => 'required|[^a-zA-Z,/|]'
        ]);

        $model = $this->getModelName();
        $daysCombination = new $model([
            'days' => $request['days']
        ]);

        try
        {
            $daysCombination->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($daysCombination), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

        if (!$request->has('days'))
        {
            $request['days'] = null;
        }

        $request->validate([
            'days' => 'required|[^a-zA-Z,/|]'
        ]);

        try
        {
            $updated_daysCombination = array();
            foreach ($request->all() as $key => $value)
            {
                if ($value !== null)
                {
                    $updated_daysCombination[$key] = $value;
                }
            }

            $this->getModelName()::query()
                ->where('id', '=', $id)
                ->update($updated_daysCombination);
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
