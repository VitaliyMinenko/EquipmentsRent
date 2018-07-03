<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
	protected $table = 'equipments';

	protected $fillable = [
		'name',
		'created_at',
		'updated_at',
	];

	public function rents()
	{
		return $this->hasMany('App\Rent');
	}

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
				'equipment_id' =>$this->id,
				'start'    => $start,
				'finish'   => $finish,
				'rent_day' => $rentDay,
				'week_day' => $weekDay,
			];
			return $array;
		} else {
			return false;
		}
	}

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

	protected function prepareData($startDate, $shiftDays)
	{
		$date = Carbon::parse($startDate);
		$equipment = self::all();
		$datesArray = [];
		$limit = count($equipment);
		$i = 0;
		while($i <= $limit) {
			$equipments = self::with('rents')->whereHas('rents', function ($q) use ($date) {
				$q->where('rent_day', $date->timestamp);
			})->get();

			if(count($equipments) != 0) {
				$shiftDays--;
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
			$i++;
			if($shiftDays == 0) {
				break;
			}
			$date->addDay(1);
		}

		return $datesArray;
	}
}
