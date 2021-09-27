@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Pengisian EDOM
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">Data EDOM</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="content">
  </section>
@endsection
