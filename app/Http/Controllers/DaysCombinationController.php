<?php

namespace App\Http\Controllers;

use App\Helpers\ValidationInterface;
use App\Models\DaysCombinations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DaysCombinationController extends Controller implements ValidationInterface
{

    public function __construct()
    {
        $this->setModelName(DaysCombinations::class);

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

    public static function validateIndividually(Request $request)
    {
        $request->validate([
            'days' => 'required|[^a-zA-Z,/|]|unique:days_combinations,days'
        ]);
    }
}
