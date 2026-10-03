@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Rincian AKM Mahasiswa
            <small>Filter & Rincian Nilai Mahasiswa Per Angkatan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Rincian AKM</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Rincian AKM Mahasiswa</h3>
            </div>
            <form action="{{ url('filter_rincian_akm_kprd') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="id_prodi">Program Studi <span class="text-danger">*</span></label>
                                <select name="id_prodi" id="id_prodi" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Program Studi --</option>
                                    <optgroup label="Program Studi Utama (Semua Konsentrasi)">
                                        @foreach ($prodi_utama as $pu)
                                            <option value="{{ $pu['value'] }}">{{ $pu['label'] }}</option>
                                        @endforeach
                                    </optgroup>
                                    @if (!empty($prodi_konsentrasi))
                                        <optgroup label="Berdasarkan Konsentrasi Spesifik">
                                            @foreach ($prodi_konsentrasi as $pk)
                                                <option value="{{ $pk['id_prodi'] }}">{{ $pk['label'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="id_periodetahun">Batas Periode Tahun <span class="text-danger">*</span></label>
                                <select name="id_periodetahun" id="id_periodetahun" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Periode Tahun --</option>
                                    @foreach ($tahun as $t)
                                        <option value="{{ $t->id_periodetahun }}">{{ $t->periode_tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="id_periodetipe">Batas Periode Semester <span class="text-danger">*</span></label>
                                <select name="id_periodetipe" id="id_periodetipe" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($tipe as $tp)
                                        <option value="{{ $tp->id_periodetipe }}">{{ $tp->periode_tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Pilih Angkatan Mahasiswa (Bisa lebih dari satu)</label>
                                <div style="margin-bottom: 8px;">
                                    <button type="button" class="btn btn-xs btn-default btn-flat" id="btnSelectAllAngkatan">
                                        <i class="fa fa-check-square-o"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-xs btn-default btn-flat" id="btnDeselectAllAngkatan">
                                        <i class="fa fa-square-o"></i> Hapus Pilihan
                                    </button>
                                </div>
                                <div class="row">
                                    @foreach ($angkatan as $a)
                                        <div class="col-md-3 col-sm-4 col-xs-6">
                                            <div class="checkbox" style="margin-top: 5px; margin-bottom: 5px;">
                                                <label>
                                                    <input type="checkbox" name="id_angkatan[]" value="{{ $a->idangkatan }}" class="chk-angkatan">
                                                    <b>Angkatan {{ $a->angkatan }}</b>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="help-block"><small><i class="fa fa-info-circle"></i> Jika tidak ada angkatan yang dipilih, sistem akan menampilkan seluruh angkatan.</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> Tampilkan Rincian AKM
                    </button>
                    <a href="{{ url('home') }}" class="btn btn-default btn-flat">Batal</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('custom_js')
<script>
    $(document).ready(function () {
        $('#btnSelectAllAngkatan').on('click', function () {
            $('.chk-angkatan').prop('checked', true);
        });

        $('#btnDeselectAllAngkatan').on('click', function () {
            $('.chk-angkatan').prop('checked', false);
        });
    });
</script>
@endsection
