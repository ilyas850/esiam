@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    @include('prausta.prakerin.partials.nilai_prausta_style')

    <section class="content nilai-prausta">
        @include('prausta.prakerin.partials.nilai_prausta_nav', ['active' => 'pkl'])
        @include('prausta.prakerin.partials.nilai_prausta_table', [
            'title' => 'Data Nilai PKL Mahasiswa',
            'boxType' => 'box-info',
            'editRoute' => 'edit_nilai_pkl',
            'validateRoute' => 'validate_nilai_pkl',
            'unvalidateRoute' => 'unvalidate_nilai_pkl',
        ])
    </section>
@endsection
