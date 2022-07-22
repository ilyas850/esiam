@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Matakuliah Mengulang mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="10%">
                                <center>NIM</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th width="10%">
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>
                                    Matakuliah
                                </center>
                            </th>
                            <th>
                                <center>Nilai</center>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->prodi }}
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $key->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $key->angkatan }}
                                    </center>
                                </td>
                                <td>
                                    {{ $key->makul }}
                                </td>
                                <td>
                                    <center>
                                        {{ $key->nilai_AKHIR }}

                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
