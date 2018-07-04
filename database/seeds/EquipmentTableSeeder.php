<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $id = DB::table('equipments')->insertGetId([
            'name' => 'Excavator 011',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('rents')->insert([
            'equipment_id' => $id,
            'week_day' => 'Tue',
            'rent_day' => 1530576000,
            'start' => 1530608400,
            'finish' => 1530615600,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('rents')->insert([
            'equipment_id' => $id,
            'week_day' => 'Wed',
            'rent_day' => 1530662400,
            'start' => 1530684000,
            'finish' => 1530712800,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);


        $id = DB::table('equipments')->insertGetId([
            'name' => 'Tipper lorry 002',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('rents')->insert([
            'equipment_id' => $id,
            'week_day' => 'Tue',
            'rent_day' => 1530576000,
            'start' => 1530619200,
            'finish' => 1530622800,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $id = DB::table('equipments')->insertGetId([
            'name' => 'Excavator 012',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('rents')->insert([
            'equipment_id' => $id,
            'week_day' => 'Thu',
            'rent_day' => 1530748800,
            'start' => 1530770400,
            'finish' => 1530784800,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $id = DB::table('equipments')->insertGetId([
            'name' => 'Excavator 005',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('rents')->insert([
            'equipment_id' => $id,
            'week_day' => 'Thu',
            'rent_day' => 1530748800,
            'start' => 1530783900,
            'finish' => 1530792000,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
