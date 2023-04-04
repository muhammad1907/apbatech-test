<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
Use App\Antrean;


class AntreanController extends Controller
{
    public function getStatusAntrean(Request $request, $kode_poli, $tanggal_periksa)
    {


        $antrean = DB::table('antriansoal')
            ->select('namapoli', 'nomorantrean', DB::raw('COUNT(*) as totalantrean'), 
                     DB::raw('SUM(IF(statusdipanggil = 0, 1, 0)) as sisaantrean'),
                     DB::raw('MAX(IF(statusdipanggil = 1, keluhan, "")) as keterangan'))
            ->where('kodepoli', $kode_poli)
            ->where('tglpriksa', $tanggal_periksa)
            ->groupBy('namapoli', 'nomorantrean')
            ->first();


        if ($antrean) {

            $response = [
                'namapoli' => $antrean->namapoli,
                'totalantrean' => $antrean->totalantrean,
                'sisaantrean' => $antrean->sisaantrean,
                'antreanpanggil' => $antrean->nomorantrean,
                'keterangan' => $antrean->keterangan
            ];
            
            $metadata = [
                'message' => 'Ok',
                'code' => 200
            ];

            return response()->json(compact('response', 'metadata'));
        } else {

            $metadata = [
                'message' => 'Data tidak ditemukan',
                'code' => 201
            ];

            return response()->json(['metadata' => $metadata]);
        }
    }

    public function ambilAntrean(Request $request)
    {
   
        $validator = Validator::make($request->all(), [
            'nomorkartu' => 'required|string',
            'nik' => 'required|string',
            'kodepoli' => 'required|string',
            'tglpriksa' => 'required|date',
            'keluhan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'metadata' => [
                    'code' => 201,
                    'message' => 'Input tidak valid'
                ]
            ]);
        }


        $antrean = Antrean::where('nomorkartu', $request->nomorkartu)->first();
        if ($antrean) {
          
            if ($antrean->statusdipanggil == 0) {

                return response()->json([
                    'metadata' => [
                        'code' => 200,
                        'message' => 'Antrean berhasil diambil'
                    ],
                    'nomorantrean' => $antrean->nomorantrean,
                    'totalantrean' => Antrean::count(),
                    'sisaantrean' => Antrean::where('statusdipanggil', 0)->count()
                ]);
            } else {
                
                return response()->json([
                    'metadata' => [
                        'code' => 201,
                        'message' => 'Anda sudah pernah dipanggil'
                    ]
                ]);
            }
        } else {

            $antrean = new Antrean;
            $antrean->nomorkartu = $request->nomorkartu;
            $antrean->nik = $request->nik;
            $antrean->kodepoli = $request->kodepoli;
            $antrean->tglpriksa = $request->tglpriksa;
            $antrean->keluhan = $request->keluhan;
            $antrean->statusdipanggil = 0;
            $antrean->id = Antrean::count() + 1;
            $antrean->save();

       
            return response()->json([
                'metadata' => [
                    'code' => 202,
                    'message' => 'Antrean baru berhasil dibuat'
                ],
                'nomorantrean' => $antrean->nomorantrean,
                'totalantrean' => Antrean::count(),
                'sisaantrean' => Antrean::where('statusdipanggil', 0)->count()
            ]);
        }
    }

    public function getSisaAntrean(Request $request, $nomorkartu_jkn, $kode_poli, $tanggalperiksa)
    {

        $antrean = Antrean::where('nomorkartu', $nomorkartu_jkn)->first();
        if (!$antrean) {
            return response()->json([
                'metadata' => [
                    'code' => 201,
                    'message' => 'Nomor kartu JKN tidak terdaftar'
                ]
            ]);
        }


        $poli = Antrean::where('kodepoli', $kode_poli)->first();
        if (!$poli) {
            return response()->json([
                'metadata' => [
                    'code' => 201,
                    'message' => 'Kode poli tidak valid'
                ]
            ]);
        }


        if (!strtotime($tanggalperiksa)) {
            return response()->json([
                'metadata' => [
                    'code' => 201,
                    'message' => 'Tanggal periksa tidak valid'
                ]
            ]);
        }


        $sisa_antrean = Antrean::where('kodepoli', $kode_poli)
                                ->where('tglpriksa', $tanggalperiksa)
                                ->where('statusdipanggil', 0)
                                ->count();


        $antrean_panggil = Antrean::where('kodepoli', $kode_poli)
                                    ->where('tglpriksa', $tanggalperiksa)
                                    ->where('statusdipanggil', 1)
                                    ->first();

        return response()->json([
            'response' => [
                'nomorantrean' => $antrean->nomorantrean,
                'namapoli' => $poli->namapoli,
                'sisaantrean' => $sisa_antrean,
                'antreanpanggil' => $antrean_panggil ? $antrean_panggil->nomorantrean : '',
                'keterangan' => ''
            ],
            'metadata' => [
                'code' => 200,
                'message' => 'Ok'
            ]
        ]);
    }

    public function batalAntrean(Request $request)
    {
       
        $nomorkartu = $request->input('nomorkartu');


        $antrean = Antrean::where('nomorkartu', $nomorkartu)->first();

      
        if ($antrean) {
            $antrean->statusdipanggil = 1;
            $antrean->save();

    
            return response()->json([
                'metadata' => [
                    'code' => 200,
                    'message' => 'OK'
                ]
            ]);
        }

        return response()->json([
            'metadata' => [
                'code' => 201,
                'message' => 'Antrean tidak ditemukan'
            ]
        ]);
    }

}
