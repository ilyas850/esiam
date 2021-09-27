@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="row">
      @foreach ($info as $item)
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <div class="product-img"><center>
              @if (($item->file) != null)
              <a href="{{ asset('/data_file/'.$item->file) }}" target="_blank">{{$item->file}}</a>
            <!--<img src="{{ asset('/data_file/'.$item->file) }}" width="320px" height="200px" alt="...">-->
              @else
                {{-- <img src="/adminlte/img/default.jpg" alt="User Avatar" width="320px" height="200px"> --}}
               @endif
<br>
              <span > Post on
                {{ date('l, Y F d', strtotime($item->created_at)) }}, {{$item->created_at->diffForHumans()}}
              </span></a>
            </center>
          </div>
          </
            <div class="caption">
              <h3>{{$item->judul}}</h3>
              {{-- <p>{{$item->deskripsi}}</p> --}}
              <p><a href="/lihat_kprd/{{$item->id_informasi}}" class="btn btn-primary" role="button">Lihat selengkapnya</a> </p>
            </div>
          </div>
        </div>
      @endforeach
    </div>

  </section>
@endsection
