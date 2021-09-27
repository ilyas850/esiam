@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-primary">
      <div class="panel-body">
        <center><h3>Kartu Hasil Studi Mahasiswa (Final-Term)</h3></center>
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
            <br>
            <div class="box">
              <table class="table table-condensed">
                <tr>
                  <th rowspan="2"style="width: 10px" align=center><center>No</center></th>
                  <th rowspan="2"><center>Kode</center></th>
                  <th rowspan="2"><center>Matakuliah</center></th>
                  <th colspan="2"><center>SKS</center></th>
                  <th colspan="2"><center>Nilai</center></th>
                  <th rowspan="2"><center>Nilai x SKS</center></th>
                </tr>
                <tr>
                  <th><center>Teori</center></th>
                  <th><center>Praktek</center></th>
                  <th><center>Huruf</center></th>
                  <th><center>Angka</center></th>
                </tr>
                <?php $no=1; ?>
                  @foreach ($krs as $item)
                <tr>
                  <td>{{$no++}}</td>
                  <td><center>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->kode}}
                      @endif
                    @endforeach
                  </center></td>
                  <td>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->makul}}
                      @endif
                    @endforeach
                  </td>
                  <td><center>
                      @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->akt_sks_teori}}
                      @endif
                    @endforeach
                      <!--{{$item->akt_sks_teori}}-->

                  </center></td>
                  <td><center>
                       @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->akt_sks_praktek}}
                      @endif
                    @endforeach
                      <!--{{$item->akt_sks_praktek}}-->

                  </center></td>
                  <td><center>{{$item->nilai_AKHIR}}</center></td>
                  <td><center>{{$item->nilai_ANGKA}}

                  </center></td>
                  <td>
                    <center>
                      @foreach ($mk as $makul)
                        @if ($item->id_makul == $makul->idmakul)
                          @if ($item->nilai_AKHIR == 'A')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*4}}
                          @elseif ($item->nilai_AKHIR == 'B+')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*3.5}}
                          @elseif ($item->nilai_AKHIR == 'B')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*3.0}}
                          @elseif ($item->nilai_AKHIR == 'C+')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*2.5}}
                          @elseif ($item->nilai_AKHIR == 'C')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*2.0}}
                          @elseif ($item->nilai_AKHIR == 'D')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*1.0}}
                          @elseif ($item->nilai_AKHIR == 'E')
                            {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*0.0}}
                          @elseif ($item->nilai_AKHIR == null)
                            0
                          @endif
                        @endif
                      @endforeach
                    </center>
                  </td>
                </tr>
                  @endforeach
                <tr>
                  <td colspan="3"><center>Jumlah</center></td>
                  <td colspan="2"><center><b>{{$sks}}</b></center></td>
                  <td colspan="2"></td>
                  <td><center><b>{{$nia->akt_sks}}</b></center></td>
                </tr>
                <tr>
                  <td colspan="3"><center>Indeks Prestasi Semester (IPS)</center></td>
                  <td colspan="2"><center><b>
                    @if($sks_nilai == null)
                        0
                    @elseif($sks_nilai != null)
                        {{ round($nia->akt_sks/$sks, 2) }}
                    @endif</b></center></td>
                  <td colspan="3"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
