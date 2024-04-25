<?php

namespace App\Imports;

use App\Models\Announcement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class AnnouncementImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Announcement([
            'name' => (string) $row['nama_lengkap'],
            'phone' => (string) $row['nomor_hp'],
            'city_of_birth' => (string) $row['tempat_lahir'],
            'date_of_birth' => (string) $row['tanggal_lahir'],
            'total_score' => (int) $row['total_score'],
            'address_from' => (string) $row['asal_daerah'],
            'school' => (string) $row['asal_instansi'],
            'status_id' => (string) $row['status'],
        ]);
    }
}