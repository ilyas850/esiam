@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    @include('prausta.prakerin.partials.nilai_prausta_style')

    <section class="content nilai-prausta">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="nilai-page-title">Nilai PKL dan Magang</h3>
                <div class="nilai-page-subtitle">Pilih tipe penilaian untuk melihat, mengedit, mengunduh form, atau melakukan validasi nilai mahasiswa.</div>
            </div>
        </div>

        @include('prausta.prakerin.partials.nilai_prausta_nav', ['active' => ''])
    </section>
@endsection
