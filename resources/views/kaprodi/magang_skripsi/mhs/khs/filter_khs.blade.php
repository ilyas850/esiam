@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter KHS Mahasiswa</h3>
            </div>
            <div class="box-body">

                <form class="form" role="form" action="{{ url('filter_khs') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Tahun Akademik - Semester</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($data as $key)
                                    <option value="{{ $key->id_periodetahun }},{{ $key->id_periodetipe }}">
                                        {{ $key->periode_tahun }} - {{ $key->periode_tipe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Tipe KHS</label>
                            <select class="form-control" name="tipe_khs" required>
                                <option></option>
                                <option value="UTS">MID TERM</option>
                                <option value="FINAL">FINAL TERM</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-info ">Lihat</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </section>
@endsection
