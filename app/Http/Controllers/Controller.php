<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
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
            $room = $this->getModelName()::findOrFail($id);
        }
        catch (\Throwable $error)
        {
            return self::throw($error, 404);
        }

        return new Response(json_encode($room), 200);
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
}
