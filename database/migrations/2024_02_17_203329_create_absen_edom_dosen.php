<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsenEdomDosen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `absen_edom_dosen`;
        CREATE PROCEDURE `absen_edom_dosen`
        (IN `id_periodetahun` INT,
        IN `id_periodetipe` INT,
        IN `jenis_absen` VARCHAR(50))

        BEGIN
            IF jenis_absen = 'dosen' THEN
                SELECT a.id_kurperiode, a.id_dosen, c.nama, COUNT(DISTINCT(b.id_student)) AS jml_mhs
                FROM kurikulum_periode a
                JOIN dosen c ON c.iddosen = a.id_dosen
                LEFT JOIN edom_transaction b ON b.id_kurperiode = a.id_kurperiode
                WHERE a.id_periodetahun = id_periodetahun AND a.id_periodetipe = id_periodetipe AND a.status = 'ACTIVE'
                GROUP BY a.id_dosen
                ORDER BY c.nama ASC;
            ELSEIF jenis_absen = 'matakuliah' THEN
                SELECT kp.id_kurperiode, mk.idmakul, mk.kode, mk.makul, prd.prodi, COUNT(DISTINCT(et.id_student)) AS jml_mhs
                FROM kurikulum_periode kp
                JOIN matakuliah mk ON mk.idmakul = kp.id_makul
                LEFT JOIN edom_transaction et ON et.id_kurperiode = kp.id_kurperiode
                JOIN prodi prd ON prd.id_prodi = kp.id_prodi
                WHERE kp.id_periodetahun = id_periodetahun AND kp.id_periodetipe = id_periodetipe AND kp.status = 'ACTIVE'
                GROUP BY kp.id_makul
                ORDER BY mk.makul ASC;
            END IF;
        END;";

        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
