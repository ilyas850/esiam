@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @include('mhs.onboarding_pwd')
    </section>
@endsection
