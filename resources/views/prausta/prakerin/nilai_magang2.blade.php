@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    @include('prausta.prakerin.partials.nilai_prausta_style')

    <section class="content nilai-prausta">
        @include('prausta.prakerin.partials.nilai_prausta_nav', ['active' => 'magang2'])
        @include('prausta.prakerin.partials.nilai_prausta_table', [
            'title' => 'Data Nilai Magang 2 Mahasiswa',
            'boxType' => 'box-warning',
            'editRoute' => 'edit_nilai_magang2',
            'validateRoute' => 'validate_nilai_magang2',
            'unvalidateRoute' => 'unvalidate_nilai_magang2',
        ])
    </section>
@endsection
