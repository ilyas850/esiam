@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
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
                        {{-- <div class="col-xs-3">
                            <label>Kode PraUSTA</label>
                            <select class="form-control" name="id_masterkode_prausta">
                                <option></option>
                                @foreach ($listprausta as $makul)
                                    <option value="{{ $makul->id_masterkode_prausta }}">{{ $makul->kode_prausta }} -
                                        {{ $makul->nama_prausta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <label>Program Studi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->id_prodi }}">{{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
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
        </div>
    </section>
@endsection
