<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Inpnilai;
use App\Models\Kompnilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//cadangan script benar
// class NilaiImport implements ToCollection
// {
//     protected $kelas_id;
//     public $headers;

//     public function __construct($kelas_id)
//     {
//         $this->kelas_id = $kelas_id;
//         $this->headers = [];
//     }

//     public function collection(Collection $rows)
//     {
//     $isFirstRow = true;
//     $headerMap = [];
//     $mahasiswaData = [];

//     DB::beginTransaction();
//     try {
//         foreach ($rows as $row) {
//             if ($isFirstRow) {
//                 $this->headers = $row->toArray();
//                 dd($this->headers);
//                 $isFirstRow = false;

//                 $headerLabels = array_slice($this->headers, 3, count($this->headers) - 5);
//                 foreach ($headerLabels as $label) {
//                     $komponen = Kompnilai::whereRaw('LOWER(label) = ?', [strtolower(trim($label))])->first();
//                     if ($komponen) {
//                         $headerMap[] = $komponen->id;
//                     } else {
//                         throw new \Exception("Kolom nilai \"$label\" tidak ditemukan di database.");
//                     }
//                 }

//                 continue;
//             }

//             $nim = $row[1];
//             $mahasiswa = Mahasiswa::where('nim', $nim)->first();

//             if ($mahasiswa) {
//                 $nilaiList = array_slice($row->toArray(), 3, count($this->headers) - 5); // Abaikan kolom Absolut & Relatif

//                 foreach ($nilaiList as $index => $nilai) {
//                     if ($nilai !== null) {
//                         $mahasiswaData[] = [
//                             'kelas_id' => $this->kelas_id,
//                             'nim' => $nim,
//                             'kompnilai_id' => $headerMap[$index],
//                             'nilai' => $nilai,
//                         ];
//                     }
//                 }
//             } else {
//                 throw new \Exception("Mahasiswa dengan NIM \"$nim\" tidak ditemukan di database.");
//             }
//         }

//         Inpnilai::insert($mahasiswaData);

//         DB::commit();
//     } catch (\Exception $e) {
//         DB::rollBack();
//         throw $e;
//     }
//     }
// }


//script coba baru

class NilaiImport implements ToCollection
{
    protected $kelas_id;
    public $headers;

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
        $this->headers = [];
    }

public function collection(Collection $rows)
{
    $isFirstRow = true;
    $headerMap = [];
    $mahasiswaData = [];

    DB::beginTransaction();
    try {
        foreach ($rows as $row) {
            if ($isFirstRow) {
                $this->headers = $row->toArray();
                $isFirstRow = false;

                $headerLabels = array_slice($this->headers, 3, count($this->headers) - 5); // Abaikan kolom Absolut & Relatif
                foreach ($headerLabels as $label) {
                    $komponen = Kompnilai::whereRaw('LOWER(label) = ?', [strtolower(trim($label))])->first(); // Trim spasi dan case-insensitive
                    if ($komponen) {
                        $headerMap[] = $komponen->id;
                    } else {
                        throw new \Exception("Kolom nilai \"$label\" tidak ditemukan di database.");
                    }
                }

                continue;
            }

            $nim = $row[1];
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();

            if ($mahasiswa) {

                $nilaiList = array_slice($row->toArray(), 3, count($this->headers) - 5);

                foreach ($nilaiList as $index => $nilai) {
                    if ($nilai !== null) {
                        $rubrikNilai = DB::table('rubnilais')
                            ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
                            ->where('rubnilais.kelas_id', $this->kelas_id)
                            ->where('kompnilais.id', $headerMap[$index])
                            ->select('rubnilais.id as rubnilai_id')
                            ->first();

                        if ($rubrikNilai) {
                            $mahasiswaData[] = [
                                'kelas_id' => $this->kelas_id,
                                'nim' => $nim,
                                'kompnilai_id' => $headerMap[$index],
                                'rubnilai_id' => $rubrikNilai->rubnilai_id,
                                'nilai' => $nilai,
                            ];
                        } else {
                            throw new \Exception("Rubrik nilai untuk NIM \"$nim\" tidak ditemukan di kelas ini.");
                        }
                    }
                }
            } else {
                throw new \Exception("Mahasiswa dengan NIM \"$nim\" tidak ditemukan di database.");
            }
        }

        Inpnilai::insert($mahasiswaData);

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

}
