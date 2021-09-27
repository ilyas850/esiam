@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Informasi selengkapnya</h3>
          </div>
          <div class="row">
            <div class="col-md-7">
              <div class="box-body">
                 @if (($info->file) != null)
                    <a href="{{ asset('/data_file/'.$info->file) }}" target="_blank">{{$info->file}}</a>
                  @else
                    Tidak ada file
                   @endif
              </div>
            </div>
            <div class="col-md-5">
              <div class="box-body">
                <h3>{{ $info->judul}}</h3>
                <p>{{ $info->deskripsi}}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
