@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        {{-- <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">PraUSTA Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('kode_prausta') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Kode PraUSTA - Prodi</label>
                            <select name="id_masterkode_prausta" class="form-control">
                                <option></option>
                                @foreach ($list as $ls)
                                    <option value="{{ $ls->id_masterkode_prausta }}">{{ $ls->kode_prausta }} /
                                        {{ $ls->nama_prausta }} ( {{ $ls->prodi }} - {{ $ls->konsentrasi }} )</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Angkatan</label>
                            <select class="form-control" name="idangkatan" required>
                                <option></option>
                                @foreach ($angkatan as $tipee)
                                    <option value="{{ $tipee->idangkatan }}">{{ $tipee->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div> --}}

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">PraUSTA Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_nilai_prausta') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select name="id_periodetahun" class="form-control" required>
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Periode Tipe</label>
                            <select name="id_periodetipe" class="form-control" required>
                                <option></option>
                                @foreach ($tipe as $tp)
                                    <option value="{{ $tp->id_periodetipe }}">{{ $tp->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Prodi</label>
                            <select name="kodeprodi" class="form-control" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Tipe PraUSTA</label>
                            <select name="tipe_prausta" class="form-control" required>
                                <option value=""></option>
                                <option value="PKL">PKL</option>
                                <option value="SEMPRO & TA">SEMPRO & TA</option>
                                <option value="MAGANG 1">MAGANG 1</option>
                                <option value="MAGANG 2">MAGANG 2</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
    </section>
@endsection
