@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dosen Politeknik META Industri</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                            @foreach ($data as $item)
                                <li>
                                    @if ($item->idkelamin == '1')
                                        <img src="{{ asset('adminlte/img/man.png') }}" alt="User Image" height="25%"
                                            width="25%">
                                    @else
                                        <img src="{{ asset('adminlte/img/girl.png') }}" alt="User Image" height="25%"
                                            width="25%">
                                    @endif
                                    <a class="users-list-name" href="#">{{ $item->nama }}, {{ $item->akademik }}</a>
                                    <span class="users-list-date">{{ $item->email }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
@endsection
