@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-primary">
      <div class="panel-body">
        <center><h3>Kartu Hasil Studi Mahasiswa (Mid-Term)</h3></center>
        <div class="row">
          <div class="col-md-12">
            <table width="100%">
              <tr>
                <td><b>TA Semester</b></td>
                <td>:</td>
                <td><b><u>
                    @foreach ($thn as $TA)
                      {{ $TA->periode_tahun }}
                    @endforeach
                    @foreach ($tp as $key)
                      {{ $key->periode_tipe }}
                    @endforeach
                  </b></u>
                </td>
                <td align=right>Jumlah SKS Maksimal&ensp; </td>
                <td> : </td>
                <td>24</td>
              </tr>
              <tr>
                <td><b>Nama</b></td>
                <td>:</td>
                <td><b><u>{{ $mhs->nama }}</b></u></td>
                <td align=right>SKS Tempuh&ensp; </td>
                <td> : </td>
                <td>{{$sks}}</td>
              </tr>
              <tr>
                <td><b>NIM</b></td>
                <td>:</td>
                <td><b><u>{{ $mhs->nim }}</u></b></td>
              </tr>
              <tr>
                <td><b>Program Studi</b></td><td> : </td>
                <td><b><u>
                      @if ($mhs->kodeprodi ==23)
                          Teknik Industri
                        @elseif ($mhs->kodeprodi ==22)
                                Teknik Komputer
                              @elseif ($mhs->kodeprodi ==24)
                                  Farmasi
                          @endif
                  </u></b>
                </td>
              </tr>
              <tr>
                <td><b>Kelas</b></td><td>:</td>
                <td><b><u>
                  @if ($mhs->idstatus ==1)
                        Reguler A
                      @elseif ($mhs->idstatus ==2)
                          Reguler C
                        @elseif ($mhs->idstatus ==3)
                            Reguler B
                    @endif
                  </b></u>
                </td>
              </tr>
            </table>
            <hr>
            <a class="btn btn-warning" href="{{url('unduh_khs_mid')}}">Unduh KHS-Mid</a>
            <hr>
            <div class="box">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px"><center>No</center></th>
                  <th><center>Kode</center></th>
                  <th><center>Matakuliah</center></th>
                  <th><center>SKS Teori</center></th>
                  <th><center>SKS Praktek</center></th>
                  <th><center>Nilai Angka</center></th>
                </tr>
                <?php $no=1; ?>
                  @foreach ($krs as $item)
                  
                <tr>
                  <td><center>{{$no++}}</center></td>
                  <td><center>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->kode}}
                      @endif
                    @endforeach
                  </center></td>
                  <td><center>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->makul}}
                      @endif
                    @endforeach
                  </center></td>
                  <td><center>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->akt_sks_teori}}
                      @endif
                    @endforeach
                  </center></td>
                  <td><center>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->akt_sks_praktek}}
                      @endif
                    @endforeach
                  </center></td>
                 
                  <td><center>
                      {{$item->nilai_UTS}}
                    </center></td>
                  
                  
                </tr>
                  @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
