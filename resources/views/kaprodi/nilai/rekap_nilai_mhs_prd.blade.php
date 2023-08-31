@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Rekap Nilai Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Mahasiswa</center>
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
                                <center>Jumlah MK</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">
                                    {{ $no++ }}
                                </td>
                                <td>
                                    {{ $key->mhs }}
                                </td>
                                <td>
                                    {{ $key->prodi }}
                                </td>
                                <td align="center">
                                    {{ $key->kelas }}
                                </td>
                                <td align="center">{{ $key->angkatan }}</td>
                                <td align="center">{{ $key->jml_mk }}</td>
                                <td align="center">
                                    <a href="/cek_rekap_nilai_mhs_kprd/{{ $key->idstudent }}"
                                        class="btn btn-info btn-xs">Cek
                                        nilai</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
