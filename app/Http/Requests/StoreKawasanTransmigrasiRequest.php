<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreKawasanTransmigrasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string', 'in:KTM,UPT,SP'],
            'kabupaten' => ['required', 'string', 'in:Kabupaten Gorontalo,Kabupaten Boalemo,Kabupaten Pohuwato,Kabupaten Gorontalo Utara'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'desa' => ['required', 'string', 'max:255'],
            'tahun_penempatan' => ['required', 'integer', 'min:1970', 'max:2030'],
            'jumlah_kk' => ['required', 'integer', 'min:0'],
            'jumlah_jiwa' => ['required', 'integer', 'min:0'],
            'kk_shm' => ['required', 'integer', 'min:0'],
            'kk_non_shm' => ['required', 'integer', 'min:0'],
            'luas_ha' => ['required', 'numeric', 'min:0'],
            'luas_shm_ha' => ['nullable', 'numeric', 'min:0'],
            'luas_non_shm_ha' => ['nullable', 'numeric', 'min:0'],
            'luas_lahan_pekarangan_ha' => ['nullable', 'numeric', 'min:0'],
            'luas_lahan_usaha_1_ha' => ['nullable', 'numeric', 'min:0'],
            'luas_lahan_usaha_2_ha' => ['nullable', 'numeric', 'min:0'],
            'target_bidang_shm' => ['nullable', 'integer', 'min:0'],
            'bidang_shm_terbit' => ['nullable', 'integer', 'min:0'],
            'bidang_shm_proses' => ['nullable', 'integer', 'min:0'],
            'tahapan_shm' => ['nullable', 'string', 'max:255'],
            'tahapan_shm_custom' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'status_perkembangan' => ['nullable', 'string', 'max:100'],
            'skor_kemandirian' => ['nullable', 'integer', 'between:0,100'],
            'komoditas_unggulan' => ['nullable'],
            'program_pemberdayaan' => ['nullable'],
            'program_pemberdayaan_custom' => ['nullable', 'string', 'max:255'],
            'jumlah_kegiatan_pemberdayaan' => ['nullable', 'integer', 'min:0'],
            'sarana_prasarana' => ['nullable', 'array'],
            'asal_transmigran' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
