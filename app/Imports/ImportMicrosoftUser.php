<?php

namespace App\Imports;

use App\Microsoft_user;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMicrosoftUser implements WithHeadingRow, ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $cekdata = Microsoft_user::where('username', $row['username'])->first();
            if ($cekdata == null) {
                Microsoft_user::create([
                    'id_student' => $row['id'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                ]);
            } elseif ($cekdata != null) {
                $id = $cekdata->username;
                $pass = $cekdata->password;

                $detail = Microsoft_user::where('username', $id)->update([
                    'id_student' => $row['id'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                ]);
            }
        }
    }
}
