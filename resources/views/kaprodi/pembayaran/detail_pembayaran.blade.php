@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Detail Pembayaran Mahasiswa Politeknik META Industri</h3>
                <table width="100%">
                    <tr>
                        <td width="10%">Nama</td>
                        <td width="1%">:</td>
                        <td>{{ $mhs->nama }}</td>

                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td> : </td>
                        <td>{{ $mhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th style="width: 10px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Item Pembayaran</center>
                            </th>
                            <th>
                                <center>Nominal Pembayaran</center>
                            </th>
                            <th>
                                <center>Beasiswa</center>
                            </th>
                            <th>
                                <center>Nominal harus dibayar</center>
                            </th>
                            <th>
                                <center>Nominal telah dibayar</center>
                            </th>
                            <th>
                                <center>Tunggakan</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    {{ $item->item }}
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency($key_total->daftar)
                                    @elseif($item->item == 'Perlengkapan Awal')
                                        @currency($key_total->awal)
                                    @elseif($item->item == 'Dana Pengembangan')
                                        @currency($key_total->dsp)
                                    @elseif($item->item == 'Biaya SPP 1')
                                        @currency($key_total->spp1)
                                    @elseif($item->item == 'Biaya SPP 2')
                                        @currency($key_total->spp2)
                                    @elseif($item->item == 'Biaya SPP 3')
                                        @currency($key_total->spp3)
                                    @elseif($item->item == 'Biaya SPP 4')
                                        @currency($key_total->spp4)
                                    @elseif($item->item == 'Biaya SPP 5')
                                        @currency($key_total->spp5)
                                    @elseif($item->item == 'Biaya SPP 6')
                                        @currency($key_total->spp6)
                                    @elseif($item->item == 'Biaya SPP 7')
                                        @currency($key_total->spp7)
                                    @elseif($item->item == 'Biaya SPP 8')
                                        @currency($key_total->spp8)
                                    @elseif($item->item == 'Biaya SPP 9')
                                        @currency($key_total->spp9)
                                    @elseif($item->item == 'Biaya SPP 10')
                                        @currency($key_total->spp10)
                                    @elseif($item->item == 'Biaya SPP 11')
                                        @currency($key_total->spp11)
                                    @elseif($item->item == 'Biaya SPP 12')
                                        @currency($key_total->spp12)
                                    @elseif($item->item == 'Biaya SPP 13')
                                        @currency($key_total->spp13)
                                    @elseif($item->item == 'Biaya SPP 14')
                                        @currency($key_total->spp14)
                                    @elseif($item->item == 'Prakerin')
                                        @currency($key_total->prakerin)
                                    @elseif($item->item == 'Seminar')
                                        @currency($key_total->seminar)
                                    @elseif($item->item == 'Sidang')
                                        @currency($key_total->sidang)
                                    @elseif($item->item == 'Wisuda')
                                        @currency($key_total->wisuda)
                                    @endif

                                </td>
                                <td align="center">
                                    @if ($key_beasiswa->idbeasiswa == null)
                                        0%
                                    @else
                                        @if ($item->item == 'Pendaftaran')
                                            {{ $key_beasiswa->daftar }}
                                        @elseif($item->item == 'Perlengkapan Awal')
                                            {{ $key_beasiswa->awal }}
                                        @elseif($item->item == 'Dana Pengembangan')
                                            {{ $key_beasiswa->dsp }}
                                        @elseif($item->item == 'Biaya SPP 1')
                                            {{ $key_beasiswa->spp1 }}
                                        @elseif($item->item == 'Biaya SPP 2')
                                            {{ $key_beasiswa->spp2 }}
                                        @elseif($item->item == 'Biaya SPP 3')
                                            {{ $key_beasiswa->spp3 }}
                                        @elseif($item->item == 'Biaya SPP 4')
                                            {{ $key_beasiswa->spp4 }}
                                        @elseif($item->item == 'Biaya SPP 5')
                                            {{ $key_beasiswa->spp5 }}
                                        @elseif($item->item == 'Biaya SPP 6')
                                            {{ $key_beasiswa->spp6 }}
                                        @elseif($item->item == 'Biaya SPP 7')
                                            {{ $key_beasiswa->spp7 }}
                                        @elseif($item->item == 'Biaya SPP 8')
                                            {{ $key_beasiswa->spp8 }}
                                        @elseif($item->item == 'Biaya SPP 9')
                                            {{ $key_beasiswa->spp9 }}
                                        @elseif($item->item == 'Biaya SPP 10')
                                            {{ $key_beasiswa->spp10 }}
                                        @elseif($item->item == 'Biaya SPP 11')
                                            {{ $key_beasiswa->spp11 }}
                                        @elseif($item->item == 'Biaya SPP 12')
                                            {{ $key_beasiswa->spp12 }}
                                        @elseif($item->item == 'Biaya SPP 13')
                                            {{ $key_beasiswa->spp13 }}
                                        @elseif($item->item == 'Biaya SPP 14')
                                            {{ $key_beasiswa->spp14 }}
                                        @elseif($item->item == 'Prakerin')
                                            {{ $key_beasiswa->prakerin }}
                                        @elseif($item->item == 'Seminar')
                                            {{ $key_beasiswa->seminar }}
                                        @elseif($item->item == 'Sidang')
                                            {{ $key_beasiswa->sidang }}
                                        @elseif($item->item == 'Wisuda')
                                            {{ $key_beasiswa->wisuda }}
                                        @endif
                                        %
                                    @endif
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency($key_total->daftar - ($key_beasiswa->daftar * $key_total->daftar) / 100)
                                    @elseif($item->item == 'Perlengkapan Awal')
                                        @currency($key_total->awal - ($key_beasiswa->awal * $key_total->awal) / 100)
                                    @elseif($item->item == 'Dana Pengembangan')
                                        @currency($key_total->dsp - ($key_beasiswa->dsp * $key_total->dsp) / 100)
                                    @elseif($item->item == 'Biaya SPP 1')
                                        @currency($key_total->spp1 - ($key_beasiswa->spp1 * $key_total->spp1) / 100)
                                    @elseif($item->item == 'Biaya SPP 2')
                                        @currency($key_total->spp2 - ($key_beasiswa->spp2 * $key_total->spp2) / 100)
                                    @elseif($item->item == 'Biaya SPP 3')
                                        @currency($key_total->spp3 - ($key_beasiswa->spp3 * $key_total->spp3) / 100)
                                    @elseif($item->item == 'Biaya SPP 4')
                                        @currency($key_total->spp4 - ($key_beasiswa->spp4 * $key_total->spp4) / 100)
                                    @elseif($item->item == 'Biaya SPP 5')
                                        @currency($key_total->spp5 - ($key_beasiswa->spp5 * $key_total->spp5) / 100)
                                    @elseif($item->item == 'Biaya SPP 6')
                                        @currency($key_total->spp6 - ($key_beasiswa->spp6 * $key_total->spp6) / 100)
                                    @elseif($item->item == 'Biaya SPP 7')
                                        @currency($key_total->spp7 - ($key_beasiswa->spp7 * $key_total->spp7) / 100)
                                    @elseif($item->item == 'Biaya SPP 8')
                                        @currency($key_total->spp8 - ($key_beasiswa->spp8 * $key_total->spp8) / 100)
                                    @elseif($item->item == 'Biaya SPP 9')
                                        @currency($key_total->spp9 - ($key_beasiswa->spp9 * $key_total->spp9) / 100)
                                    @elseif($item->item == 'Biaya SPP 10')
                                        @currency($key_total->spp10 - ($key_beasiswa->spp10 * $key_total->spp10) / 100)
                                    @elseif($item->item == 'Biaya SPP 11')
                                        @currency($key_total->spp11 - ($key_beasiswa->spp11 * $key_total->spp11) / 100)
                                    @elseif($item->item == 'Biaya SPP 12')
                                        @currency($key_total->spp12 - ($key_beasiswa->spp12 * $key_total->spp12) / 100)
                                    @elseif($item->item == 'Biaya SPP 13')
                                        @currency($key_total->spp13 - ($key_beasiswa->spp13 * $key_total->spp13) / 100)
                                    @elseif($item->item == 'Biaya SPP 14')
                                        @currency($key_total->spp14 - ($key_beasiswa->spp14 * $key_total->spp14) / 100)
                                    @elseif($item->item == 'Prakerin')
                                        @currency($key_total->prakerin - ($key_beasiswa->prakerin * $key_total->prakerin) / 100)
                                    @elseif($item->item == 'Seminar')
                                        @currency($key_total->seminar - ($key_beasiswa->seminar * $key_total->seminar) / 100)
                                    @elseif($item->item == 'Sidang')
                                        @currency($key_total->sidang - ($key_beasiswa->sidang * $key_total->sidang) / 100)
                                    @elseif($item->item == 'Wisuda')
                                        @currency($key_total->wisuda - ($key_beasiswa->wisuda * $key_total->wisuda) / 100)
                                    @endif
                                </td>
                                <td align="right">
                                    @currency($item->telah_dibayar)
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency($key_total->daftar - ($key_beasiswa->daftar * $key_total->daftar) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Perlengkapan Awal')
                                        @currency($key_total->awal - ($key_beasiswa->awal * $key_total->awal) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Dana Pengembangan')
                                        @currency($key_total->dsp - ($key_beasiswa->dsp * $key_total->dsp) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 1')
                                        @currency($key_total->spp1 - ($key_beasiswa->spp1 * $key_total->spp1) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 2')
                                        @currency($key_total->spp2 - ($key_beasiswa->spp2 * $key_total->spp2) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 3')
                                        @currency($key_total->spp3 - ($key_beasiswa->spp3 * $key_total->spp3) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 4')
                                        @currency($key_total->spp4 - ($key_beasiswa->spp4 * $key_total->spp4) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 5')
                                        @currency($key_total->spp5 - ($key_beasiswa->spp5 * $key_total->spp5) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 6')
                                        @currency($key_total->spp6 - ($key_beasiswa->spp6 * $key_total->spp6) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 7')
                                        @currency($key_total->spp7 - ($key_beasiswa->spp7 * $key_total->spp7) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 8')
                                        @currency($key_total->spp8 - ($key_beasiswa->spp8 * $key_total->spp8) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 9')
                                        @currency($key_total->spp9 - ($key_beasiswa->spp9 * $key_total->spp9) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 10')
                                        @currency($key_total->spp10 - ($key_beasiswa->spp10 * $key_total->spp10) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 11')
                                        @currency($key_total->spp11 - ($key_beasiswa->spp11 * $key_total->spp11) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 12')
                                        @currency($key_total->spp12 - ($key_beasiswa->spp12 * $key_total->spp12) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 13')
                                        @currency($key_total->spp13 - ($key_beasiswa->spp13 * $key_total->spp13) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Biaya SPP 14')
                                        @currency($key_total->spp14 - ($key_beasiswa->spp14 * $key_total->spp14) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Prakerin')
                                        @currency($key_total->prakerin - ($key_beasiswa->prakerin * $key_total->prakerin) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Seminar')
                                        @currency($key_total->seminar - ($key_beasiswa->seminar * $key_total->seminar) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Sidang')
                                        @currency($key_total->sidang - ($key_beasiswa->sidang * $key_total->sidang) / 100 - $item->telah_dibayar)
                                    @elseif($item->item == 'Wisuda')
                                        @currency($key_total->wisuda - ($key_beasiswa->wisuda * $key_total->wisuda) / 100 - $item->telah_dibayar)
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
