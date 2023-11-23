@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>
                                <center>No.</center>
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
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>No. Transkrip</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($nilai as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $key->nim }}</td>
                                <td>{{ $key->nama }}</td>
                                <td align="center">{{ $key->prodi }}</td>
                                <td align="center">{{ $key->kelas }}</td>
                                <td align="center">{{ $key->angkatan }}</td>
                                <td align="center">
                                    @if ($key->no_transkrip == null)
                                        <a href="/cek_transkrip/{{ $key->idstudent }}" class="btn btn-info btn-xs">Input
                                        </a>
                                    @else
                                        {{ $key->no_transkrip }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($key->no_transkrip != null)
                                        <a href="/lihat_transkrip_sementara/{{ $key->id_transkrip }}"
                                            class="btn btn-success btn-xs">Lihat</a>
                                        <a href="/edit_transkrip_sementara/{{ $key->id_transkrip }}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <a href="/hapus_transkrip_sementara/{{ $key->id_transkrip }}"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('anda yakin akan menghapus ini?')">Hapus</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
