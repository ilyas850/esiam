@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Yudisium Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>NIK</th>
                            <th>Ijazah</th>
                            <th>KTP</th>
                            <th>Foto</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->tmpt_lahir }}</td>
                                <td>{{ $item->tgl_lahir->isoFormat('D MMMM Y') }}
                                </td>
                                <td>{{ $item->nik }}</td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_ijazah }}" target="_blank">
                                        File </a></td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_ktp }}" target="_blank">
                                        File </a></td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_foto }}"
                                        target="_blank"> File </a></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
