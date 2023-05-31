@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $datamhs->nama }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>
                            {{ $datamhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> {{ $datamhs->nim }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>
                            {{ $datamhs->kelas }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>Matakuliah</th>
                            <th>Hari</th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>Ruangan</th>
                            <th>
                                <center>SKS (T/P)</center>
                            </th>
                            <th>Dosen</th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($val as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>

                                <td align="center">{{ $item->semester }}</td>
                                <td>{{ $item->kode }}/{{ $item->makul }}</td>
                                <td> {{ $item->hari }}</td>
                                <td align="center"> {{ $item->jam }}</td>
                                <td>{{ $item->nama_ruangan }}</td>
                                <td>
                                    <center>
                                        {{ $item->akt_sks_teori }}/{{ $item->akt_sks_praktek }}
                                    </center>
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <center>
                                        @if ($item->remark == 1)
                                            <span class="badge bg-green">Valid</span>
                                        @elseif ($item->remark == 0 or $item->remark == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        
                                        @endif
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
