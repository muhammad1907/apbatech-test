<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class AntrianSoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('antriansoal')->insert([
            'nomorantrean' => 'A1',
            'angkaantrean' => 1,
            'norm' => '0002',
            'namapoli' => 'POLI KANDUNGAN',
            'kodepoli' => '001',
            'tglpriksa' => '2023-04-02',
            'nomorkartu' => '000000001',
            'nik' => '02020202',
            'keluhan' => 'sakit kepala',
            'statusdipanggil' => 0,
            'id' => 2,
            'created_at'=> Carbon::now()
        ]);
    }
}
