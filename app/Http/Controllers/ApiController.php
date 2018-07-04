<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Equipment;
use App\Rent;
use Illuminate\Validation\Rule;

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
		$validator = Validator::make($request->all(), [
			'date' => 'required|date_format:Y-m-d',
			'days' => 'required|integer',
		]);
		if($validator->fails()) {
			return response()->json([
				'status'  => 'Error',
				'message' => $validator->errors(),
			], 400);
		}
		$date = $request->input('date');
		$shift = $request->input('days');
		$equpment = new Equipment();
		$response = $equpment->getAvailable($date, $shift);
		if(empty($response)) {
			return response()->json([
				'status'  => 'ok',
				'message' => 'Nothing not found.',
			]);
		}

		return response()->json([
			'status'   => 'ok',
			'response' => $response,
		]);
	}

	/**
	 * Method for add new Equipment or rent date.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addRentPeriod(Request $request)
	{
		$week_days = config('app.week_days');
		$validator = Validator::make($request->all(), [
			'equipment'               => 'required|string|min:3|max:255',
			'week_day'                => 'required|string|in:' . implode(',', $week_days),
			'duration.start_duration' => 'required|string|date_format:H:i',
			'duration.end_time'       => 'required|string|date_format:H:i',

		]);
		if($validator->fails()) {
			return response()->json([
				'status'  => 'Error',
				'message' => $validator->errors(),
			], 400);
		}
		$equpmentName = $request->input('equipment');
		$weekDay = $request->input('week_day');
		$start = $request->input('duration.start_duration');
		$finish = $request->input('duration.end_time');
		$equipment = Equipment::with('rents')->firstOrCreate(['name' => $equpmentName]);
		$rentsArray = $equipment->checkAndSetDays($weekDay, $start, $finish);
		if($rentsArray) {
			$equipment->save();
			$equipment->rents()->save(new Rent($rentsArray));

			return response()->json([
				'status'  => 'ok',
				'message' => 'The date was add successfully.',
			]);
		} else {
			return response()->json([
				'status'  => 'ok',
				'message' => 'Day is not empty. Try to choose another day.',
			]);
		}
	}

}
