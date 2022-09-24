<?php
 
namespace App\Imports;
 
use App\Models\Announcement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
 
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
            'name' => $row['nama_lengkap'],
            'phone' => $row['nomor_hp'], 
            'city_of_birth' => $row['tempat_lahir'], 
            'date_of_birth' => $row['tanggal_lahir'],
            'total_score' => $row['total_score'],
            'address_from' => $row['asal_daerah'],
            'school' => $row['asal_instansi'],
            'result' => $row['status_kelolosan'],
        ]);
    }
}