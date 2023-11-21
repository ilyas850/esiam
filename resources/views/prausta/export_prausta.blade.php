@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Export Data PKL</h3>
            </div>
            <form class="form" role="form" action="{{ url('excel_pkl') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode</label>
                            <select class="form-control" name="idperiode" required>
                                <option></option>
                                @foreach ($periode as $prd)
                                    <option value="{{ $prd->id_periodetahun }},{{ $prd->id_periodetipe }}">
                                        {{ $prd->periode_tahun }} -
                                        {{ $prd->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Export Data Magang 1</h3>
            </div>
            <form class="form" role="form" action="{{ url('excel_magang') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode</label>
                            <select class="form-control" name="idperiode" required>
                                <option></option>
                                @foreach ($periode as $prd)
                                    <option value="{{ $prd->id_periodetahun }},{{ $prd->id_periodetipe }}">
                                        {{ $prd->periode_tahun }} -
                                        {{ $prd->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Export Data Magang 2</h3>
            </div>
            <form class="form" role="form" action="{{ url('excel_magang2') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode</label>
                            <select class="form-control" name="idperiode" required>
                                <option></option>
                                @foreach ($periode as $prd)
                                    <option value="{{ $prd->id_periodetahun }},{{ $prd->id_periodetipe }}">
                                        {{ $prd->periode_tahun }} -
                                        {{ $prd->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Export Tugas Akhir</h3>
            </div>
            <form class="form" role="form" action="{{ url('excel_ta') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode</label>
                            <select class="form-control" name="idperiode" required>
                                <option></option>
                                @foreach ($periode as $prd)
                                    <option value="{{ $prd->id_periodetahun }},{{ $prd->id_periodetipe }}">
                                        {{ $prd->periode_tahun }} -
                                        {{ $prd->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Export Skripsi</h3>
            </div>
            <form class="form" role="form" action="{{ url('excel_skripsi') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode</label>
                            <select class="form-control" name="idperiode" required>
                                <option></option>
                                @foreach ($periode as $prd)
                                    <option value="{{ $prd->id_periodetahun }},{{ $prd->id_periodetipe }}">
                                        {{ $prd->periode_tahun }} -
                                        {{ $prd->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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
