@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data KRS Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
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
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th>
                                <center>Jml. SKS</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            @php
                                $totalSKS = 0;
                                if (isset($item->student_records)) {
                                    foreach ($item->student_records as $record) {
                                        if ($record->status == 'TAKEN' && isset($record->kurperiode->makul)) {
                                            $makul = $record->kurperiode->makul;
                                            $totalSKS += ($makul->akt_sks_teori ?? 0) + ($makul->akt_sks_praktek ?? 0);
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nim }} - {{ $item->nama }}</td>
                                <td>{{ $item->prodi->prodi }}</td>
                                <td>{{ $item->kelas->kelas }}</td>
                                <td>{{ $item->angkatan->angkatan }} -
                                    {{ $item->intake == '1' ? 'Ganjil' : 'Genap' }}
                                </td>
                                <td>{{ $item->dosenPembimbing->dosen->nama ?? '-' }}</td>
                                <td>{{ $totalSKS }} SKS</td>

                                <td align="center">
                                    <a href="#" class="btn btn-success btn-xs" title="Lihat KRS">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ url('/krs-manual/create/' . $item->id) }}" class="btn btn-info btn-xs"
                                        title="Tambah KRS">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
