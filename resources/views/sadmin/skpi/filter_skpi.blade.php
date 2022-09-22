@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Form Isian SKPI Mahasiswa Politeknik META Industri</b></h3>
                <table width="100%">
                    <tr>
                        <td width="10%">Program Studi</td>
                        <td width="1%">:</td>
                        <td>{{ $prodi->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Angkatan</td>
                        <td>:</td>
                        <td>{{ $angkatan->angkatan }}
                        </td>
                    </tr>

                </table>
            </div>
            <div class="box-body">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th style="width: 10px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Lengkap</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nomor SKPI</center>
                            </th>
                            <th>
                                <center>Nomor Ijazah</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
