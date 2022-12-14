@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('view_ktm') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select name="idangkatan" class="form-control">
                                <option>-pilih angkatan-</option>
                                @foreach ($cekangk as $key)
                                    <option value="{{ $key->idangkatan }}">{{ $key->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="kodeprodi">
                                <option>-pilih prodi-</option>
                                @foreach ($prd as $keyprd)
                                    <option value="{{ $keyprd->kodeprodi }}">{{ $keyprd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="idstatus">
                                <option>-pilih kelas-</option>
                                @foreach ($kls as $keykls)
                                    <option value="{{ $keykls->idkelas }}">{{ $keykls->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info ">Lihat</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
