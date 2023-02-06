@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Kurikulum</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_konversi') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Kurikulum</label>
                            <select class="form-control" name="id_kurikulum">
                                <option></option>
                                @foreach ($kurikulum as $krlm)
                                    <option value="{{ $krlm->id_kurikulum }}">{{ $krlm->nama_kurikulum }} -
                                        @if ($krlm->remark == 1)
                                            Ganjil
                                        @elseif($krlm->remark == 2)
                                            Genap
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun">
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
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
                        <div class="col-xs-4">
                            <label>Program Studi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->id_prodi }}">{{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Angkatan</label>
                            <select class="form-control" name="id_angkatan" required>
                                <option></option>
                                @foreach ($angkatan as $angk)
                                    <option value="{{ $angk->idangkatan }}">{{ $angk->angkatan }}</option>
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

