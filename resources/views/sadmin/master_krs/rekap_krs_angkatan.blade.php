@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Export KRS Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_rekap_krs_angkatan') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tipe as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
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
