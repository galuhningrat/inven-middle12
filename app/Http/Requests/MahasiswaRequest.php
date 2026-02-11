<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Mengambil ID dari route untuk pengecualian unique saat update
        $mahasiswaId = $this->route('id');

        return [
            'id_users' => 'required|exists:users,id|unique:mahasiswa,id_users,' . $mahasiswaId,
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswaId . '|max:20',
            'nik' => 'required|digits:16|unique:mahasiswa,nik,' . $mahasiswaId,
            'no_kk' => 'required|digits:16|unique:mahasiswa,no_kk,' . $mahasiswaId,
            'nisn' => 'required|digits:10|unique:mahasiswa,nisn,' . $mahasiswaId,
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Katolik,Protestan,Budha,Hindu,Konghucu',
            'hp' => 'required|string|max:15',
        ];
    } // <-- Pastikan kurung ini menutup rules()

    public function messages()
    {
        return [
            'id_users.unique' => 'User ini sudah terdaftar sebagai mahasiswa',
            'nim.unique' => 'NIM sudah digunakan',
            'nik.digits' => 'NIK harus 16 digit',
            'no_kk.digits' => 'No KK harus 16 digit',
            'nisn.digits' => 'NISN harus 10 digit',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
        ];
    }
}
