@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Wisuda Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Lulus</th>
                            <th>Ukuran Toga</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>No. HP</th>
                            <th>E-mail</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <th>Alamat KTP</th>
                            <th>Alamat Domisili</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            <th>No. HP Ayah</th>
                            <th>No. HP Ibu</th>
                            <th>Alamat Ortu</th>
                            <th>Status Vaksin</th>
                            <th>File Vaksin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->tahun_lulus }}</td>
                                <td>{{ $item->ukuran_toga }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->npwp }}</td>
                                <td>{{ $item->alamat_ktp }}</td>
                                <td>{{ $item->alamat_domisili }}</td>
                                <td>{{ $item->nama_ayah }}</td>
                                <td>{{ $item->nama_ibu }}</td>
                                <td>{{ $item->no_hp_ayah }}</td>
                                <td>{{ $item->no_hp_ibu }}</td>
                                <td>{{ $item->alamat_ortu }}</td>
                                <td>{{ $item->status_vaksin }}</td>
                                <td><a href="/File Vaksin/{{ $item->id_student }}/{{ $item->file_vaksin }}" target="_blank">
                                        File Vaksin</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
