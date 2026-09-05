@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if (Auth::user()->role == 1)
            @include('layouts.admin_home')
        @elseif (Auth::user()->role == 2)
            @include('layouts.dosen_home')
        @elseif (Auth::user()->role == 3)
            @include('layouts.mhs_home')
        @elseif (Auth::user()->role == 4)
            @include('mhs.onboarding_pwd')
        @elseif (Auth::user()->role == 5)
            @include('layouts.dosenluar_home')
        @elseif (Auth::user()->role == 6)
            @include('layouts.kaprodi_home')
        @elseif (Auth::user()->role == 7)
            @include('layouts.wadir1_home')
        @elseif (Auth::user()->role == 8)
            @include('layouts.bauk_home')
        @elseif (Auth::user()->role == 9)
            @include('layouts.adminprodi_home')
        @elseif (Auth::user()->role == 10)
            @include('layouts.wadir3_home')
        @elseif (Auth::user()->role == 11)
            @include('layouts.prausta_home')
        @elseif (Auth::user()->role == 12)
            @include('layouts.gugusmutu_home')
        @endif
    </section>
@endsection
