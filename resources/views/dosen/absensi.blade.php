@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Absensi Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/entri_bap/{{ $idk }}"> BAP</a></li>
            <li class="active">Entri Absensi Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Absensi Mahasiswa</h3>
            </div>
            <form action="{{ url('save_absensi') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $idk }}">
                <input type="hidden" name="id_bap" value="{{ $id }}">
                <div class="box-body">
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Remark : Pilih sesuai aktual kehadiran</p>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="4%">
                                    <center>No</center>
                                </th>
                                <th width="8%">
                                    <center>NIM </center>
                                </th>
                                <th width="20%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="8%">
                                    <center>Kelas</center>
                                </th>
                                <th width="8%">
                                    <center>Angkatan</center>
                                </th>
                                <th width="8%">
                                    <center>Pilih</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($absen as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan }}</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_studentrecord[]"
                                                value="{{ $item->id_studentrecord }}">
                                            {{-- <input type="checkbox" name="absensi[]"
                                                value="{{ $item->id_studentrecord }}"> --}}
                                            <select name="absensi[]" class="form-control" required>
                                                <option value="HADIR"></option>
                                                <option value="ABSEN">Hadir</option>
                                                <option value="IZIN">Izin</option>
                                                <option value="SAKIT">Sakit</option>
                                                <option value="ALFA">Alfa</option>
                                            </select>
                                        </center>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <button id="simpan" class="btn btn-success btn-block" type="submit">Simpan</button>

                </div>
            </form>
        </div>
    </section>

    {{-- <script>
        $(document).ready(function() {
            $('#simpan').click(function() {
                // Menonaktifkan tombol setelah diklik
                $(this).prop('disabled', true);

                // Mencegah pengguna mengklik tombol lagi
                $(this).unbind('click');
            });
        });
    </script> --}}
@endsection
