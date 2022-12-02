@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Jadwal Ujian</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_jadwal_ujian') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <input type="hidden" name="id_periodetahun" value="{{ $tahun->id_periodetahun }}">
                            <input type="text" value="{{ $tahun->periode_tahun }}" class="form-control" readonly>
                        </div>
                        <div class="col-xs-2">
                            <label>Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tipe as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Kelas</label>
                            <select class="form-control" name="idkelas" required>
                                <option></option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->idkelas }}">{{ $kls->kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Jenis Ujian</label>
                            <select class="form-control" name="jenis_ujian" required>
                                <option></option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Jadwal Ujian <b>{{ $tahun->periode_tahun }} - {{ $tipe_aktif->periode_tipe }}</b>
                </h3>
            </div>
            <div class="box-body">
                <table class="table table-condensed" id="example8">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Jenis Ujian</center>
                            </th>
                            <th>
                                <center>Matakuliah </center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Tipe Ujian</center>
                            </th>
                            <th>
                                <center>Ruangan</center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td align="center">{{ $item->jenis_ujian }}</td>
                                <td>{{ $item->makul }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->tipe_ujian }}</td>
                                <td align="center">{{ $item->nama_ruangan }}</td>
                                <td align="center">{{ $item->jam }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
