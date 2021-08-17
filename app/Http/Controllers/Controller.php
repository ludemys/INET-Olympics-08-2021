<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Name of the model
     * @param string|object::class
     */
    protected $modelName;
    protected $fillable;

    public function __construct()
    {
        $model = new $this->modelName;
        $this->setFillable($model->getFillable());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @throws Throwable
     */
    public function index()
    {
        $rooms = $this->getModelName()::all();

        if (count($rooms) < 1)
        {
            return new Response(json_encode($rooms), 204);
        }

        return new Response(json_encode($rooms), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @throws Throwable
     */
    public function show($id)
    {
        try
        {
            $model = $this->getModelName()::findOrFail($id);
        }
        catch (\Throwable $error)
        {
            return self::throw($error, 404);
        }

        return new Response(json_encode($model), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

        foreach ($this->fillable as $column)
        {
            if ($request->has($column))
            {
                $request[$column] = null;
            }
        }

        self::validateIndividually($request);

        try
        {
            $model = array();
            foreach ($request->all() as $key => $value)
            {
                if ($value !== null)
                {
                    $model[$key] = $value;
                }
            }

            $this->getModelName()::query()
                ->where('id', '=', $id)
                ->update($model);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @throws Throwable|LogicException
     */
    public function destroy($id)
    {
        // Verifies if the register exists
        try
        {
            $room = $this->getModelName()::findOrFail($id);
        }
        catch (\Throwable $error)
        {
            return self::throw($error, 404);
        }

        //Stores a copy of the deleted model and tries deleting it
        try
        {
            $backupModel = $room;
            $room->delete();
        }
        catch (\LogicException $error)
        {
            return self::throw($error);
        }

        return new Response(json_encode($backupModel), 200);
    }

    /**
     * @param Throwable $error
     * @param int|string|null $code
     * 
     * @return Illuminate\Http\Response
     */
    protected static function throw(Throwable $error, int|string $code = null)
    {
        return new Response(
            $error->getMessage(),
            $code ? $code : $error->getCode()
        );
    }

    /**
     * Get the value of modelName
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set the value of modelName
     *
     * @return  self
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get the value of fillable
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Set the value of fillable
     *
     * @return  self
     */
    public function setFillable($fillable)
    {
        $this->fillable = $fillable;

        return $this;
    }

    public static function validateIndividually(Request $request)
    {
        self::throw(new \Exception("A server loading error has occurred", 500));
    }

    /**
     * @param string $number
     * @param int $upTo
     * 
     * @return string
     */
    protected static function addZerosToLeft(string $number, int $upTo)
    {
        $zeros = '';

        if (strlen($number) < $upTo)
        {
            for ($i = 0; $i < $upTo - $number; $i++)
            {
                $zeros .= '0';
            }
        }

        return $zeros . $number;
    }
}
