<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Rent;
use Illuminate\Http\Request;


/**
 * Base controller for Api.
 * Class ApiController
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Base api method which returned information about rent hours.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRentPeriod(Request $request)
    {
        $date = $request->input('date');
        $shift = $request->input('days');
        $equpment = new Equipment();
        $response = $equpment->getAvailable($date, $shift);
        if(empty($response)){
            return response()->json([
                'status' => 'ok',
                'message' => 'Nothing not found.',
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'response' => $response,
        ]);
    }

    /**
     * Method for add new Equipment or rent date.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRentPeriod(Request $request)
    {
        $equpmentName = $request->input('equipment');
        $weekDay = $request->input('week_day');
        $start = $request->input('duration.start_duration');
        $finish = $request->input('duration.end_time');
        $equipment = Equipment::with('rents')->firstOrCreate(['name' => $equpmentName]);
        $rentsArray = $equipment->checkAndSetDays($weekDay, $start, $finish);
        if ($rentsArray) {
            $equipment->save();
            $equipment->rents()->save(new Rent($rentsArray));

            return response()->json([
                'status' => 'ok',
                'message' => 'The date was add successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'ok',
                'message' => 'Day is not empty. Try to choose another day.',
            ]);
        }
    }

}
