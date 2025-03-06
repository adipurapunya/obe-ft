<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $prodiId;

    /**
     * Constructor untuk menerima prodi_id dari controller.
     *
     * @param int $prodiId
     */
    public function __construct(int $prodiId)
    {
        // dd('Constructor dipanggil, prodiId: ' . $prodiId);
        $this->prodiId = $prodiId;
    }

    /**
     * Method untuk memetakan data dari Excel ke dalam model Mahasiswa.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        Log::info('Tabel model digunakan: ' . (new Mahasiswa)->getTable());
        Log::info('Data yang disimpan:', [
            'nim' => $row['nim'],
            'nama_mahasiswa' => $row['nama_mahasiswa'],
            'angkatan' => $row['angkatan'],
            'smt_angkatan' => ucfirst(strtolower($row['semester_angkatan'])),
            'prodi_id' => $this->prodiId,
            'jenis_kelamin' => strtoupper($row['jenis_kelamin']),
        ]);

        return new Mahasiswa([
            'nim' => $row['nim'],
            'nama_mahasiswa' => $row['nama_mahasiswa'],
            'angkatan' => $row['angkatan'],
            'smt_angkatan' => ucfirst(strtolower($row['semester_angkatan'])),
            'prodi_id' => $this->prodiId, // Assign prodi_id dari controller
            'jenis_kelamin' => strtoupper($row['jenis_kelamin'])
        ]);
    }

    /**
     * Validasi data Excel.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nim' => 'required|unique:mahasiswa,nim',
            '*.nama_mahasiswa' => 'required|string|max:255',
            '*.angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            '*.smt_angkatan' => 'required|in:Ganjil,Genap',
            '*.jenis_kelamin' => 'required|in:L,P',
        ];
    }

    /**
     * Custom validation messages (optional).
     *
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.nim.required' => 'Kolom NIM wajib diisi.',
            '*.nim.unique' => 'NIM sudah ada dalam database.',
            '*.nama_mahasiswa.required' => 'Kolom Nama Mahasiswa wajib diisi.',
            '*.angkatan.required' => 'Kolom Angkatan wajib diisi.',
            '*.angkatan.integer' => 'Kolom Angkatan harus berupa angka.',
            '*.smt_angkatan.required' => 'Kolom Semester Angkatan wajib diisi.',
            '*.smt_angkatan.in' => 'Kolom Semester Angkatan harus diisi dengan Ganjil atau Genap.',
            '*.jenis_kelamin.required' => 'Kolom Jenis Kelamin wajib diisi.',
            '*.jenis_kelamin.in' => 'Kolom Jenis Kelamin harus diisi dengan L atau P.',
        ];
    }
}
