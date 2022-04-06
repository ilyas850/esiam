@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Tahun Akademik</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('view_nilai') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="id_periodetahun">
                                <option>-pilih tahun-</option>
                                @foreach ($add as $key)
                                    <option value="{{ $key->id_periodetahun }}">
                                        {{ $key->periode_tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="id_periodetipe">
                                <option>-pilih tipe-</option>
                                @foreach ($tpe as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="id_student" value="{{ $idmhs->idstudent }}">
                        <button type="submit" class="btn btn-info ">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
