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
            <li class="active">Edit Absensi Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Absensi Mahasiswa</h3>
            </div>
            <form action="{{ url('save_edit_absensi') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_bap" value="{{ $id }}">
                {{-- <input type="hidden" name="id_kurperiode" value="{{ $idk }}"> --}}
                <div class="box-body">
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Remark : Centang untuk mahasiswa yang hadir</p>
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
                            @foreach ($abs as $item)
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

                                            <select name="absensi[]" class="form-control" required>
                                                @if ($item->absensi == 'ABSEN')
                                                    <option
                                                        value="{{ $item->id_studentrecord }},ABSEN,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                        Hadir</option>
                                                @elseif($item->absensi == 'IZIN')
                                                    <option
                                                        value="{{ $item->id_studentrecord }},IZIN,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                        Izin</option>
                                                @elseif($item->absensi == 'SAKIT')
                                                    <option
                                                        value="{{ $item->id_studentrecord }},SAKIT,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                        Sakit</option>
                                                @elseif($item->absensi == 'ALFA')
                                                    <option
                                                        value="{{ $item->id_studentrecord }},ALFA,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                        Alfa</option>
                                                @elseif($item->absensi == null)
                                                    <option></option>
                                                @elseif($item->absensi == 'HADIR')
                                                    <option
                                                        value="{{ $item->id_studentrecord }},HADIR,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                    </option>
                                                @endif
                                                <option
                                                    value="{{ $item->id_studentrecord }},ABSEN,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                    Hadir</option>
                                                <option
                                                    value="{{ $item->id_studentrecord }},IZIN,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                    Izin</option>
                                                <option
                                                    value="{{ $item->id_studentrecord }},SAKIT,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                    Sakit</option>
                                                <option
                                                    value="{{ $item->id_studentrecord }},ALFA,{{ $item->id_absensi }},{{ $item->id_bap }}">
                                                    Alfa</option>
                                            </select>

                                            {{-- @if ($item->absensi == 'HADIR')
                                                <input type="checkbox" name="absensi[]" value="{{ $item->id_absensi }}">
                                            @elseif ($item->absensi == 'ABSEN')
                                                <input type="checkbox" name="absensi[]" value="{{ $item->id_absensi }}"
                                                    checked>
                                            @elseif($item->absensi == null)
                                                <input type="checkbox" name="abs[]"
                                                    value="{{ $item->id_studentrecord }}">
                                            @endif --}}
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
