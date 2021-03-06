@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Menu Laporan EDOM</h3>
            </div>
            <form class="form" role="form" action="{{ url('report_edom') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="col-xs-2">
                        <label>Periode tahun</label>
                        <select class="form-control" name="id_periodetahun" required>
                            <option></option>
                            @foreach ($periodetahun as $key)
                                <option value="{{ $key->id_periodetahun }}">
                                    {{ $key->periode_tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Periode tipe</label>
                        <select class="form-control" name="id_periodetipe" required>
                            <option></option>
                            @foreach ($periodetipe as $tipee)
                                <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <label>Prodi</label>
                        <select class="form-control" name="id_prodi" required>
                            <option></option>
                            @foreach ($prodi as $prd)
                                <option value="{{ $prd->id_prodi }}">{{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label>Tipe Laporan</label>
                        <select class="form-control" name="tipe_laporan" required>
                            <option></option>
                            <option value="by_makul">Per Matakuliah</option>
                            <option value="by_dosen">Per Dosen</option>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-xs-3">
                        <button type="submit" class="btn btn-info ">Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
