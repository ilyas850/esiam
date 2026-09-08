<table>
    <thead>
        <tr>
            <th colspan="12" style="font-size: 14pt; font-weight: bold; text-align: center;">
                JADWAL PERKULIAHAN {{ strtoupper($namaperiodetahun) }} - {{ strtoupper($namaperiodetipe) }}
            </th>
        </tr>
        <tr>
            <th colspan="12" style="font-size: 11pt; text-align: center;">
                Program Studi: {{ $namaprodi }}
            </th>
        </tr>
        <tr>
            <th colspan="12"></th>
        </tr>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th style="border: 1px solid #000; text-align: center;">No</th>
            <th style="border: 1px solid #000; text-align: center;">Kode MK</th>
            <th style="border: 1px solid #000; text-align: left;">Mata Kuliah</th>
            <th style="border: 1px solid #000; text-align: center;">SKS T</th>
            <th style="border: 1px solid #000; text-align: center;">SKS P</th>
            <th style="border: 1px solid #000; text-align: center;">Total SKS</th>
            <th style="border: 1px solid #000; text-align: left;">Program Studi</th>
            <th style="border: 1px solid #000; text-align: left;">Konsentrasi</th>
            <th style="border: 1px solid #000; text-align: center;">Kelas</th>
            <th style="border: 1px solid #000; text-align: left;">Dosen Pengampu</th>
            <th style="border: 1px solid #000; text-align: center;">Hari</th>
            <th style="border: 1px solid #000; text-align: center;">Jam</th>
            <th style="border: 1px solid #000; text-align: center;">Ruangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $hariMap = [
                'SENIN' => 'Senin',
                'MONDAY' => 'Senin',
                'SELASA' => 'Selasa',
                'TUESDAY' => 'Selasa',
                'RABU' => 'Rabu',
                'WEDNESDAY' => 'Rabu',
                'KAMIS' => 'Kamis',
                'THURSDAY' => 'Kamis',
                'JUMAT' => 'Jumat',
                'JUM\'AT' => 'Jumat',
                'FRIDAY' => 'Jumat',
                'SABTU' => 'Sabtu',
                'SATURDAY' => 'Sabtu',
                'MINGGU' => 'Minggu',
                'SUNDAY' => 'Minggu',
            ];
        @endphp
        @forelse ($data as $item)
            @php
                $kode = $item->kode ?? ($item->makul->kode ?? '-');
                $namaMk = is_string($item->makul) ? $item->makul : ($item->makul->makul ?? '-');
                $sksT = $item->sks_teori ?? ($item->makul ? ($item->makul->set_sks_teori ?? $item->makul->akt_sks_teori ?? 0) : 0);
                $sksP = $item->sks_praktek ?? ($item->makul ? ($item->makul->set_sks_praktek ?? $item->makul->akt_sks_praktek ?? 0) : 0);
                $totalSks = $item->total_sks ?? ($sksT + $sksP);
                $prodiNama = is_string($item->prodi) ? $item->prodi : ($item->prodi->prodi ?? '-');
                $konsentrasi = $item->daftar_konsentrasi ?? '-';
                $kelasNama = is_string($item->kelas) ? $item->kelas : ($item->kelas->kelas ?? '-');
                $dosenNama = $item->nama_dosen ?? ($item->dosen->nama ?? 'Belum Ditentukan');
                $rawHari = is_string($item->hari) ? $item->hari : ($item->hari->hari ?? '-');
                $hariLabel = $hariMap[strtoupper(trim($rawHari))] ?? ($rawHari ?: '-');
                $jamLabel = is_string($item->jam) ? $item->jam : ($item->jam->jam ?? '-');
                $ruanganLabel = $item->nama_ruangan ?? ($item->ruangan->nama_ruangan ?? '-');
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $kode }}</td>
                <td style="border: 1px solid #000;">{{ $namaMk }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $sksT }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $sksP }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $totalSks }}</td>
                <td style="border: 1px solid #000;">{{ $prodiNama }}</td>
                <td style="border: 1px solid #000;">{{ $konsentrasi }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $kelasNama }}</td>
                <td style="border: 1px solid #000;">{{ $dosenNama }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $hariLabel }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $jamLabel }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $ruanganLabel }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="13" style="border: 1px solid #000; text-align: center;">Tidak ada data jadwal perkuliahan</td>
            </tr>
        @endforelse
    </tbody>
</table>
