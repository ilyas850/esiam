@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    @include('prausta.prakerin.partials.nilai_prausta_style')

    <section class="content nilai-prausta">
        @include('prausta.prakerin.partials.nilai_prausta_nav', ['active' => 'magang'])
        @include('prausta.prakerin.partials.nilai_prausta_table', [
            'title' => 'Data Nilai Magang 1 Mahasiswa',
            'boxType' => 'box-success',
            'editRoute' => 'edit_nilai_magang',
            'validateRoute' => 'validate_nilai_magang',
            'unvalidateRoute' => 'unvalidate_nilai_magang',
        ])
    </section>
@endsection
