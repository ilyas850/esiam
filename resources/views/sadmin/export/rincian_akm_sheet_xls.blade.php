<table>
    <thead>
        <tr>
            <th colspan="10" style="font-weight: bold; font-size: 14px;">
                AKM Mahasiswa: {{ $mhs->nama }} (NIM: {{ $mhs->nim }}) - {{ $mhs->prodi }} - Angkatan {{ $mhs->angkatan }}
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">No</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Semester</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Kode Mata Kuliah</th>
            <th style="font-weight: bold; border: 1px solid #000;">Mata Kuliah</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">SKS Ambil</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">SKS Diakui</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Nilai Angka</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Nilai Huruf</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Bobot</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">SKS * Bobot</th>
        </tr>
    </thead>
    <tbody>
        @php
            $row = 2;
        @endphp
        @foreach ($courses as $index => $c)
            @php
                $row++;
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->semester_label }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->kode }}</td>
                <td style="border: 1px solid #000;">{{ $c->makul }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->sks }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->sks_diakui }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->nilai_AKHIR_angka !== null ? $c->nilai_AKHIR_angka : '-' }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->nilai_AKHIR ?: '-' }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $c->bobot }}</td>
                <td style="text-align: center; border: 1px solid #000;">=I{{ $row }}*F{{ $row }}</td>
            </tr>
        @endforeach

        @php
            $lastCourseRow = $row;
            $ipkRow = $lastCourseRow + 1;
            $ipsRow = $lastCourseRow + 2;
        @endphp

        @if (count($courses) > 0)
            <!-- Baris 1: IPK Kumulatif (hanya nilai lulus >= C) -->
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: right; border: 1px solid #000;">IPK</td>
                <td style="border: 1px solid #000;"></td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">=SUM(F3:F{{ $lastCourseRow }})</td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">=J{{ $ipkRow }}/F{{ $ipkRow }}</td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">=SUM(J3:J{{ $lastCourseRow }})</td>
            </tr>

            <!-- Baris 2: IPS Semester Berjalan (termasuk nilai D & E) -->
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: right; border: 1px solid #000;">IPS</td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">
                    @if (!empty($summary['start_current_row']) && !empty($summary['end_current_row']))
                        =SUM(E{{ $summary['start_current_row'] }}:E{{ $summary['end_current_row'] }})
                    @else
                        0
                    @endif
                </td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">
                    @if (!empty($summary['start_current_row']) && !empty($summary['end_current_row']))
                        =J{{ $ipsRow }}/E{{ $ipsRow }}
                    @else
                        0
                    @endif
                </td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000;">
                    @if (!empty($summary['start_current_row']) && !empty($summary['end_current_row']))
                        =SUMPRODUCT(E{{ $summary['start_current_row'] }}:E{{ $summary['end_current_row'] }}, I{{ $summary['start_current_row'] }}:I{{ $summary['end_current_row'] }})
                    @else
                        0
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td colspan="10" style="text-align: center; border: 1px solid #000;">Tidak ada data mata kuliah</td>
            </tr>
        @endif
    </tbody>
</table>
