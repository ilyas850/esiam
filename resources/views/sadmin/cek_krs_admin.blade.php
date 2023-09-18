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
                <div class="row">
                    @if ($b == 1)
                    @elseif ($b == 0)
                        <div class="col-md-12">
                            <label><span class="badge bg-green">Silahkan masukan matakuliah yang akan
                                    ditambahkan</span></label>
                            <form class="form-horizontal" role="form" action="{{ url('savekrs_new') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_student" value="{{ $mhss }}" />
                                <select class="form-control" name="id_kurperiode[]" onchange="this.form.submit();">
                                    <option value=""><b>-pilih matakuliah-</b></option>
                                    @foreach ($add as $key)
                                        <option value="{{ $key->id_kurperiode }}, {{ $key->idkurtrans }}">
                                            {{ $key->id_kurperiode }} -
                                            {{ $key->semester }} - {{ $key->kode }} - {{ $key->makul }} -
                                            {{ $key->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <hr>
                        </div>
                    @endif

                </div>

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
                                <td>{{ $item->makul }}</td>
                                <td> {{ $item->hari }}</td>
                                <td align="center"> {{ $item->jam }}</td>
                                <td>{{ $item->nama_ruangan }}</td>
                                <td>
                                    <center>
                                        {{ $item->sks }}
                                    </center>
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <center>
                                        @if ($item->remark == 1)
                                            <form method="POST" action="{{ url('batalkrsmhs') }}">
                                                <input type="hidden" name="id_studentrecord"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="hidden" name="id_student" value="{{ $item->idstudent }}">
                                                <input type="hidden" name="remark" value="0">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs"
                                                    title="klik untuk batal" data-toggle="tooltip" data-placement="right"
                                                    onclick="return confirm('apakah anda yakin akan membatalkan matakuliah ini?')">Batal</button>
                                            </form>
                                        @elseif ($item->remark == 0)
                                            <form method="POST" action="{{ url('batalkrsmhs') }}">
                                                <input type="hidden" name="id_studentrecord"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="hidden" name="id_student" value="{{ $item->idstudent }}">
                                                <input type="hidden" name="remark" value="1">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip"
                                                    data-placement="right">Validasi</button>
                                            </form>
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
