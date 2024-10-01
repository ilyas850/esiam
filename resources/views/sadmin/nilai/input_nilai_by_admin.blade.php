@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">List Mahasiswa <b>({{ $cekMatkul->makul->kode }} - {{ $cekMatkul->makul->makul }})</b></h3>
            </div>
            <div class="box-body">
                <form action="{{ url('save-nilai-by-admin') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_kurperiode" value="{{ $idKurperiode }}">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM - Nama Mahasiswa</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai AKHIR</center>
                                </th>
                                <th>
                                    <center>Nilai HURUF</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                @php
                                    $nimNama = "{$item->nim} - {$item->nama}";
                                    $nilaiAkhirAngka = old('nilai_AKHIR_angka.' . $key, $item->nilai_AKHIR_angka ?? 0);
                                    $nilaiAkhir = old('nilai_AKHIR.' . $key, $item->nilai_AKHIR ?? '');
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $nimNama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->angkatan }}</td>
                                    <td align="center">
                                        <input type="hidden" name="id_studentrecord[{{ $key }}]"
                                            value="{{ $item->id_studentrecord }}">
                                        <input type="number" name="nilai_AKHIR_angka[{{ $key }}]"
                                            id="nilai_akhir_angka_{{ $key }}" value="{{ $nilaiAkhirAngka }}"
                                            onchange="generateNilaiHuruf({{ $key }})">
                                    </td>
                                    <td align="center">
                                        <input type="text" name="nilai_AKHIR[{{ $key }}]"
                                            id="nilai_akhir_{{ $key }}" value="{{ $nilaiAkhir }}" readonly>
                                        <input type="hidden" name="nilai_ANGKA[{{ $key }}]"
                                            id="nilai_akhir_numeric_{{ $key }}"
                                            value="{{ old('nilai_ANGKA.' . $key, 0) }}">
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-info btn-block">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

<script>
    function generateNilaiHuruf(index) {
        // Get the nilai_akhir_angka input element
        var nilaiAngka = document.getElementById('nilai_akhir_angka_' + index).value;
        var nilaiHuruf = '';
        var nilaiNumeric = 0;

        // Convert nilai angka to nilai huruf
        if (nilaiAngka >= 80) {
            nilaiHuruf = 'A';
            nilaiNumeric = 4;
        } else if (nilaiAngka >= 75) {
            nilaiHuruf = 'B+';
            nilaiNumeric = 3.5;
        } else if (nilaiAngka >= 70) {
            nilaiHuruf = 'B';
            nilaiNumeric = 3;
        } else if (nilaiAngka >= 65) {
            nilaiHuruf = 'C+';
            nilaiNumeric = 2.5;
        } else if (nilaiAngka >= 60) {
            nilaiHuruf = 'C';
            nilaiNumeric = 2;
        } else if (nilaiAngka >= 50) {
            nilaiHuruf = 'D';
            nilaiNumeric = 1;
        } else {
            nilaiHuruf = 'E';
            nilaiNumeric = 0;
        }

        // Set the nilai_huruf input value
        document.getElementById('nilai_akhir_' + index).value = nilaiHuruf;
        document.getElementById('nilai_akhir_numeric_' + index).value = nilaiNumeric;
    }
</script>
