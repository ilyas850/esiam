@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Permohonan Keringanan Absensi Mahasiswa </h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Status</center>
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
                                <td align="center">
                                    {{ $key->nim }}
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td align="center">
                                    {{ $key->prodi }}
                                </td>
                                <td align="center">
                                    {{ $key->kelas }}
                                </td>
                                <td>
                                    {{ $key->makul }}
                                </td>
                                <td align="center">
                                    {{ $key->semester }}
                                </td>
                                <td align="center">
                                    @if ($key->permohonan == 'MENGAJUKAN')
                                        <span class="badge bg-yellow"> {{ $key->permohonan }}</span>
                                    @elseif($key->permohonan == 'DISETUJUI')
                                        <span class="badge bg-green"> {{ $key->permohonan }}</span>
                                    @elseif($key->permohonan == 'TIDAK DISETUJUI')
                                        <span class="badge bg-red"> {{ $key->permohonan }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    <a href="/acc_keringanan_absensi_luar/{{ $key->id_studentrecord }}" class="btn btn-info btn-xs"
                                        title="Klik untuk Setujui Keringanan"><i class="fa fa-check"></i></a>
                                    <a href="/reject_keringanan_luar/{{ $key->id_studentrecord }}"
                                        class="btn btn-danger btn-xs" title="Klik untuk Tolak Keringanan"><i
                                            class="fa fa-close"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
