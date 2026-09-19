@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Rincian AKM Mahasiswa
            <small>Export Rincian Aktivitas Kuliah Mahasiswa Multi-Sheet</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Export Data</a></li>
            <li class="active">Rincian AKM</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Rincian AKM Mahasiswa</h3>
            </div>
            <form action="{{ url('filter_rincian_akm') }}" method="POST">
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
                                <label>Pilih Angkatan Mahasiswa <span class="text-danger">*</span></label>
                                <div style="margin-bottom: 8px;">
                                    <button type="button" class="btn btn-default btn-xs" id="btn-select-all">
                                        <i class="fa fa-check-square-o"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-default btn-xs" id="btn-deselect-all">
                                        <i class="fa fa-square-o"></i> Hapus Pilihan
                                    </button>
                                </div>
                                <div class="row">
                                    @foreach ($angkatan as $akt)
                                        <div class="col-md-2 col-sm-3 col-xs-6" style="margin-bottom: 5px;">
                                            <div class="checkbox" style="margin-top: 0; margin-bottom: 0;">
                                                <label>
                                                    <input type="checkbox" name="id_angkatan[]" value="{{ $akt->idangkatan }}" class="chk-angkatan">
                                                    <b>Angkatan {{ $akt->angkatan }}</b>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted"><i class="fa fa-info-circle"></i> Centang satu atau beberapa angkatan sekaligus (misal: Angkatan 2022 & 2023).</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> Tampilkan Data & Rincian
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btnSelectAll = document.getElementById('btn-select-all');
            var btnDeselectAll = document.getElementById('btn-deselect-all');
            var checkboxes = document.querySelectorAll('.chk-angkatan');

            if (btnSelectAll) {
                btnSelectAll.addEventListener('click', function () {
                    checkboxes.forEach(function (chk) { chk.checked = true; });
                });
            }

            if (btnDeselectAll) {
                btnDeselectAll.addEventListener('click', function () {
                    checkboxes.forEach(function (chk) { chk.checked = false; });
                });
            }
        });
    </script>
@endsection
