@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    @if ($cekdata == 0)
      <div class="col-md-6 ">
        <div class="box box-primary">
          <div class="box-header with-border">
            <a class="btn btn-danger" href="{{url('pengajuan_seminar_prakerin')}}">Ajukan Seminar Prakerin</a>
          </div>
        </div>
      </div>

    @elseif ($cekdata != 0)
      sudah ada
    @endif
  </section>
@endsection
