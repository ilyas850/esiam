@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Data Pembayaran Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">

                <table id="example8" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="3%">
                                <center>No </center>
                            </th>
                            <th>
                                <center>NIM </center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa </center>
                            </th>
                            <th>
                                <center> Program Studi</center>
                            </th>
                            <th>
                                <center> Kelas</center>
                            </th>
                            <th>
                                <center> Angkatan</center>
                            </th>
                            <th>
                                <center> Total Pembayaran</center>
                            </th>
                            <th>
                                <center> Total Telah Dibayar</center>
                            </th>
                            <th>
                                <center> Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td>{{ $item->nama }}</td>
                                <td align="center">{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->angkatan }}</td>
                                <td align="right">@currency($item->total_harus_bayar)</td>
                                <td align="right">@currency($item->total_sudah_bayar)</td>
                                <td align="center">
                                    <a href="detail_pembayaran_mhs/{{ $item->idstudent }}"
                                        class="btn btn-warning btn-xs">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
