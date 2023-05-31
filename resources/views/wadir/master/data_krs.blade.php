@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data KRS Mahasiswa <b>{{ $thn->periode_tahun }} - {{ $tp->periode_tipe }}</b></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('view_krs_wadir1') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-2">
                            <select class="form-control" name="id_tahun" required>
                                <option></option>
                                @foreach ($tahun as $item)
                                    <option value="{{ $item->id_periodetahun }}">{{ $item->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <select class="form-control" name="id_tipe" required>
                                <option></option>
                                @foreach ($tipe as $item)
                                    <option value="{{ $item->id_periodetipe }}">{{ $item->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success ">Tampilkan</button>
                    </form>
                </div>
                <br>
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
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $app)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>

                                <td>
                                    <center>{{ $app->nim }}</center>
                                </td>
                                <td>{{ $app->nama }}</td>
                                <td>
                                    {{ $app->prodi }}
                                </td>
                                <td>
                                    <center>
                                        {{ $app->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $app->angkatan }}
                                    </center>
                                <td>
                                    {{ $app->nama_dsn }}
                                </td>
                                <td>
                                    <center>
                                        @if ($app->remark == 1)
                                            <span class="badge bg-green">Valid</span>
                                        @elseif ($app->remark == 0 or $app->remark == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form action="{{ url('cek_krs_wadir1') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_student" value="{{ $app->id_student }}">
                                            <input type="hidden" name="id_periodetahun"
                                                value="{{ $thn->id_periodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $tp->id_periodetipe }}">

                                            <button type="submit" class="btn btn-info btn-xs" title="Klik untuk cek KRS"><i
                                                    class="fa fa-eye"></i></button>
                                        </form>

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
