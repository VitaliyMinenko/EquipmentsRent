<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
	/**
	 * @var string Name of the table
	 */
	protected $table = 'equipments';

	/**
	 * @var array  Names od the fields.
	 */
	protected $fillable = [
		'name',
		'created_at',
		'updated_at',
	];

	/**
	 * Relation with child model.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function rents()
	{
		return $this->hasMany('App\Rent');
	}

	/**
	 * Get available dates and hours when we have our equipment in rent.
	 *
	 * @param $startDate
	 * @param $shiftDays
	 *
	 * @return array
	 */
	public function getAvailable($startDate, $shiftDays)
	{
		$preparedData = $this->prepareData($startDate, $shiftDays);
		if(empty($preparedData)) {
			return $preparedData;
		}
		$countedData = $this->countRents($preparedData);
		$result = $this->convertToReadbleFormat($countedData);

		return $result;
	}

	/**
	 * Check are we have this date if not set new date.
	 *
	 * @param $weekDay
	 * @param $start
	 * @param $finish
	 *
	 * @return array|bool
	 */
	public function checkAndSetDays($weekDay, $start, $finish)
	{
		$start = explode(':', $start);
		$finish = explode(':', $finish);
		$now = Carbon::now();
		$rentDay = '';
		for($i = 1 ; $i <= 7 ; $i++) {
			if($now->shortEnglishDayOfWeek == $weekDay) {
				$rentDay = Carbon::parse($now->format('Y-m-d'))->timestamp;
				$start = Carbon::parse($now->format('Y-m-d'))->addHours($start[0])->addMinutes($start[1])->timestamp;
				$finish = Carbon::parse($now->format('Y-m-d'))->addHours($finish[0])->addMinutes($finish[1])->timestamp;
				break;
			}
			$now->addDay(1);
		}
		$rents = $this->rents->where('equipment_id', $this->id)->where('rent_day', $rentDay)->first();
		if(empty($rents)) {
			$array = [
				'equipment_id' => $this->id,
				'start'        => $start,
				'finish'       => $finish,
				'rent_day'     => $rentDay,
				'week_day'     => $weekDay,
			];

			return $array;
		} else {
			return false;
		}
	}

	/**
	 * Convert timestamps to human readable format.
	 *
	 * @param $countedData
	 *
	 * @return mixed
	 */
	protected function convertToReadbleFormat($countedData)
	{
		foreach($countedData as $key => $dateArr) {
			foreach($dateArr as $k => $dates) {
				$dateArr[$k] = [
					Carbon::createFromTimestamp($dates[0])->format('H:i'),
					Carbon::createFromTimestamp($dates[1])->format('H:i'),
				];
			}
			$countedData[Carbon::createFromTimestamp($key)->toDateString()] = $dateArr;
			unset($countedData[$key]);
		}

		return $countedData;
	}

	/**
	 * Count our rent period per day.
	 *
	 * @param $preparedData
	 *
	 * @return mixed
	 */
	protected function countRents($preparedData)
	{
		foreach($preparedData as $key => $rentTerms) {
			usort($rentTerms, function ($a, $b) {
				return $a[0] - $b[0];
			});
			$preparedData[$key] = $this->getWorkHours($rentTerms);
		}

		return $preparedData;
	}

	/**
	 * Get work hours and sum it per day.
	 *
	 * @param $arr
	 *
	 * @return mixed
	 */
	protected function getWorkHours($arr)
	{
		$lenght = count($arr);
		$i = 0;
		while($lenght >= $i) {
			if(isset($arr[$i])) {
				foreach($arr as $k => $v) {
					if($k != $i) {
						if(in_array($v[0], range($arr[$i][0], $arr[$i][1]))) {
							$arr[$i][1] = $v[1];
							unset($arr[$k]);
						}
					}
				}
			}
			$i++;
		}

		return $arr;
	}

	/**
	 * Get all equipment and take equipment from next N days.
	 *
	 * @param $startDate
	 * @param $shiftDays
	 *
	 * @return array
	 */
	protected function prepareData($startDate, $shiftDays)
	{
		$date = Carbon::parse($startDate);
		$equipment = self::with('rents')->whereHas('rents', function ($q) use ($date) {
			$q->where('rent_day', '>=', $date->timestamp);
		})->get();
		$datesArray = [];
		$limit = count($equipment);
		$i = 0;
		if(!empty($limit)) {
			while($i <= $limit) {
				$equipments = self::with('rents')->whereHas('rents', function ($q) use ($date) {
					$q->where('rent_day', $date->timestamp);
				})->get();

				if(count($equipments) != 0) {
					$shiftDays--;
					$i++;
					foreach($equipments as $k => $equipment) {
						foreach($equipment->rents as $key => $rent) {
							if($date->timestamp == $rent['rent_day']) {
								$datesArray[$rent['rent_day']][] = [
									$rent['start'],
									$rent['finish'],
								];
							}
						}
					}
				}
				if($shiftDays == 0) {
					break;
				}
				$date->addDay(1);
			}
		}

		return $datesArray;
	}
}
