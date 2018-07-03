<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
	protected $table = 'rents';

	protected $fillable = [
		'equipment_id',
		'week_day',
		'rent_day',
		'start',
		'finish',
		'created_at',
		'updated_at',
	];

	public function equipment()
	{
		return $this->belongsTo('App\Equipment');
	}
}
