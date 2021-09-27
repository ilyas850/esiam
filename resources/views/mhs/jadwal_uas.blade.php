@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Jadwal UAS</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
          <tr>
            <th>Jadwal Ujian</th>
            <th>Waktu Ujian</th>
            <th>Kode</th>
            <th>Matakuliah</th>
            <th>Ruangan</th>
          </tr>
          @foreach ($record as $key)
            <tr>
              <td>
                @foreach ($uts as $keyuts)
                  @if ($key->id_makul == $keyuts->id_makul)
                    {{ date('l, d F Y', strtotime($keyuts->tanggal_ujian)) }}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($uts as $keyuts)
                  @if ($key->id_makul == $keyuts->id_makul)
                    @foreach ($jam as $keyjam)
                      @if ($keyuts->id_jam == $keyjam->id_jam)
                        {{$keyjam->jam}} - {{ date('H:i', strtotime($keyjam->jam)+ (60*90))  }}
                      @endif
                    @endforeach
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($mk as $keymk)
                  @if ($key->id_makul == $keymk->idmakul)
                    {{$keymk->kode}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($mk as $keymk)
                  @if ($key->id_makul == $keymk->idmakul)
                    {{$keymk->makul}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($uts as $keyuts)
                  @if ($key->id_makul == $keyuts->id_makul)
                    @foreach ($rng as $keyrng)
                      @if ($keyuts->id_ruangan == $keyrng->id_ruangan)
                        {{$keyrng->nama_ruangan}}
                      @endif
                    @endforeach
                  @endif
                @endforeach
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </section>
@endsection
