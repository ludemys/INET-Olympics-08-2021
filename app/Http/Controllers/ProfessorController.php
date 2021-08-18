<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Dflydev\DotAccessData\Exception\DataException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfessorController extends Controller
{
    public function __construct()
    {
        $this->setModelName(Professor::class);

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
        $professor = new $model([
            'full_name' => $request['full_name'],
            'dni' => $request['dni'],
            'phone_number' => $request['phone_number'],
            'birthdate' => $request['birthdate'],
            'entry_date' => $request['entry_date'],
        ]);

        try
        {
            $professor->saveOrFail();
        }
        catch (\Throwable $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($professor), 201);
    }

    /**
     * Returns the oldest professor by antiquity
     * 
     * @return Response
     * @throws Exception
     */
    public function oldest()
    {
        $minEntryDate = Professor::query()
            ->get('entry_date')
            ->min('entry_date');

        $professor = Professor::query()
            ->where('entry_date', '=', $minEntryDate)
            ->first();

        try
        {
            if (!$professor)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($professor), 200);
    }

    /**
     * Returns the youngest professor by antiquity
     * 
     * @return Response
     * @throws Exception
     */
    public function youngest()
    {
        $minEntryDate = Professor::query()
            ->get('entry_date')
            ->max('entry_date');

        $professor = Professor::query()
            ->where('entry_date', '=', $minEntryDate)
            ->first();

        try
        {
            if (!$professor)
            {
                throw new Exception('There was an unknown error while getting your data. Please, try again', 500);
            }
        }
        catch (Exception $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($professor), 200);
    }


    public static function validateIndividually(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:50|regex:[^a-zA-Z ]',
            'dni' => 'required|string|max:8|unique:professors,dni',
            'phone_number' => 'required|string|max:15|regex:[^0-9\-]',
            'birthdate' => 'required|date|date_format:d-m-Y',
            'entry_date' => 'required|date|date_format:d-m-Y|after:birthdate',
        ]);
    }
}
