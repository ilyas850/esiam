@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Tabel Nilai</h3>
            </div>
            {{-- <form action="{{url('save_nilai_angka')}}" method="post">
        {{ csrf_field() }} --}}
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>

                            <th>
                                <center>Pilih</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($nilai as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>
                                        {{ $key->prodi }} - {{ $key->konsentrasi }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->kelas }}
                                    </center>
                                </td>

                                <td>
                                    <center>
                                        <a href="/cek_nilai_mhs_admin/{{$key->id_student}}" class="btn btn-info btn-xs">Cek Nilai</a>
                                        
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
