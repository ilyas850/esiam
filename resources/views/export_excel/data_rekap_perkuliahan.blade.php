<table>
    <thead>
        <tr>
            <th colspan="13" style="font-size: 14pt; font-weight: bold; text-align: center;">
                REKAPITULASI PERKULIAHAN & BAP {{ strtoupper($namaperiodetahun) }} - {{ strtoupper($namaperiodetipe) }}
            </th>
        </tr>
        <tr>
            <th colspan="13" style="font-size: 11pt; text-align: center;">
                Program Studi: {{ $namaprodi }}
            </th>
        </tr>
        <tr>
            <th colspan="13"></th>
        </tr>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th style="border: 1px solid #000; text-align: center;">No</th>
            <th style="border: 1px solid #000; text-align: center;">Kode MK</th>
            <th style="border: 1px solid #000; text-align: left;">Mata Kuliah</th>
            <th style="border: 1px solid #000; text-align: center;">SKS (T/P)</th>
            <th style="border: 1px solid #000; text-align: left;">Program Studi</th>
            <th style="border: 1px solid #000; text-align: left;">Konsentrasi</th>
            <th style="border: 1px solid #000; text-align: center;">Kelas</th>
            <th style="border: 1px solid #000; text-align: left;">Dosen Pengampu</th>
            <th style="border: 1px solid #000; text-align: center;">Jumlah Pertemuan</th>
            <th style="border: 1px solid #000; text-align: center;">Online</th>
            <th style="border: 1px solid #000; text-align: center;">Offline</th>
            <th style="border: 1px solid #000; text-align: center;">Persentase</th>
            <th style="border: 1px solid #000; text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse ($data as $item)
            @php
                $jmlPer = $item->jml_per ?? 0;
                $jmlOnline = $item->jml_online ?? 0;
                $jmlOffline = $item->jml_offline ?? 0;
                $percentage = round(min(($jmlPer / 16) * 100, 100), 1);
                $tercapai = $jmlPer >= 16;
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->kode ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->nama_makul ?? $item->makul ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->sks ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->prodi ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->daftar_konsentrasi ?: '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->kelas ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $item->nama ?? 'Belum Ditentukan' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $jmlPer }} / 16</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $jmlOnline }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $jmlOffline }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $percentage }}%</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; color: {{ $tercapai ? '#008000' : '#c00000' }};">
                    {{ $tercapai ? 'Tercapai' : 'Belum Tercapai' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="13" style="border: 1px solid #000; text-align: center;">Tidak ada data rekap perkuliahan</td>
            </tr>
        @endforelse
    </tbody>
</table>
