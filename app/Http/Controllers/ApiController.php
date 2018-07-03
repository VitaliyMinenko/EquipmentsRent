<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        $shift = $request->input('N');
        return response()->json([
            'status' => 'ok',
            'response' => $date,
        ]);
        dd($date);
        $equpment = new Equipment();
        $response = $equpment->getAvailable('2018-07-01', '3');

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

        $weekDay = 'Sat';
        $start = '14:00';
        $finish = '18:00';
        $equpment = Equipment::with('rents')->firstOrCreate(['name' => 'Excavator 0113344']);
        $rentsArray = $equpment->checkAndSetDays($weekDay, $start, $finish);
        if ($rentsArray) {
            $equpment->save();
            $equpment->rents()->save(new Rent($rentsArray));

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
