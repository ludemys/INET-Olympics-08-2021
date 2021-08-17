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
     * Search for a group of register based on a parameter passed through GET method.
     * 
     * @return Response
     * @throws Throwable
     */
    // public function search()
    // {
    //     // Checks if there is any parameter.
    //     try
    //     {
    //         if (!isset($_GET))
    //         {
    //             throw new DataException('Wrong URL: Parameter missed', 404);
    //         }
    //     }
    //     catch (DataException $error)
    //     {
    //         self::throw($error, 404);
    //     }

    //     // Checks if the parameter passed by GET is in $fillable property on this class and the classes with foreign keys on it.
    //     try
    //     {
    //         $flag = false;
    //         foreach ($this->getFillable() as $column)
    //         {
    //             if (in_array(strtolower($column), $_GET))
    //             {
    //                 $flag = true;
    //                 break;
    //             }
    //         }
    //         if (!$flag)
    //         {
    //         }
    //     }
    //     catch (DataException $error)
    //     {
    //         self::throw($error, 404);
    //     }
    // }

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
