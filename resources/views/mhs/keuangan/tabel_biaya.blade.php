@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tabel Biaya Kuliah</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No.</center>
                            </th>
                            <th>
                                <center>Item Bayar</center>
                            </th>
                            <th>
                                <center>Biaya </center>
                            </th>
                            <th>
                                <center>Beasiswa</center>
                            </th>
                            <th>
                                <center>Total Pembayaran</center>
                            </th>
                            <th>
                                <center>Telah dibayarkan</center>
                            </th>
                            <th>
                                <center>Sisa Pembayaran</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($itembayar as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td> {{ $item->item }} </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency ($biaya->daftar)
                                    @elseif ($item->item == 'Perlengkapan Awal')
                                        @currency ( $biaya->awal )
                                    @elseif ($item->item == 'Dana Pengembangan')
                                        @currency ( $biaya->dsp )
                                    @elseif ($item->item == 'Biaya SPP 1')
                                        @currency ( $biaya->spp1 )
                                    @elseif ($item->item == 'Biaya SPP 2')
                                        @currency ( $biaya->spp2 )
                                    @elseif ($item->item == 'Biaya SPP 3')
                                        @currency ( $biaya->spp3 )
                                    @elseif ($item->item == 'Biaya SPP 4')
                                        @currency ( $biaya->spp4 )
                                    @elseif ($item->item == 'Biaya SPP 5')
                                        @currency ( $biaya->spp5 )
                                    @elseif ($item->item == 'Biaya SPP 6')
                                        @currency ( $biaya->spp6 )
                                    @elseif ($item->item == 'Biaya SPP 7')
                                        @currency ( $biaya->spp7 )
                                    @elseif ($item->item == 'Biaya SPP 8')
                                        @currency ( $biaya->spp8 )
                                    @elseif ($item->item == 'Biaya SPP 9')
                                        @currency ( $biaya->spp9)
                                    @elseif ($item->item == 'Biaya SPP 10')
                                        @currency ( $biaya->spp10 )
                                    @elseif ($item->item == 'Biaya SPP 11')
                                        @currency ( $biaya->spp11 )
                                    @elseif ($item->item == 'Biaya SPP 12')
                                        @currency ( $biaya->spp12 )
                                    @elseif ($item->item == 'Biaya SPP 13')
                                        @currency ( $biaya->spp13)
                                    @elseif ($item->item == 'Biaya SPP 14')
                                        @currency ( $biaya->spp14 )
                                    @elseif ($item->item == 'Prakerin')
                                        @currency ( $biaya->prakerin )
                                    @elseif ($item->item == 'Seminar')
                                        @currency ( $biaya->seminar)
                                    @elseif ($item->item == 'Sidang')
                                        @currency ( $biaya->sidang )
                                    @elseif ($item->item == 'Wisuda')
                                        @currency ( $biaya->wisuda )
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        @if ($cb == null)
                                            0 %
                                        @elseif($cb != null)
                                            @if ($item->item == 'Pendaftaran')
                                                {{ $cb->daftar }} %
                                            @elseif ($item->item == 'Perlengkapan Awal')
                                                {{ $cb->awal }} %
                                            @elseif ($item->item == 'Dana Pengembangan')
                                                {{ $cb->dsp }} %
                                            @elseif ($item->item == 'Biaya SPP 1')
                                                {{ $cb->spp1 }} %
                                            @elseif ($item->item == 'Biaya SPP 2')
                                                {{ $cb->spp2 }} %
                                            @elseif ($item->item == 'Biaya SPP 3')
                                                {{ $cb->spp3 }} %
                                            @elseif ($item->item == 'Biaya SPP 4')
                                                {{ $cb->spp4 }} %
                                            @elseif ($item->item == 'Biaya SPP 5')
                                                {{ $cb->spp5 }} %
                                            @elseif ($item->item == 'Biaya SPP 6')
                                                {{ $cb->spp6 }} %
                                            @elseif ($item->item == 'Biaya SPP 7')
                                                {{ $cb->spp7 }} %
                                            @elseif ($item->item == 'Biaya SPP 8')
                                                {{ $cb->spp8 }} %
                                            @elseif ($item->item == 'Biaya SPP 9')
                                                {{ $cb->spp9 }} %
                                            @elseif ($item->item == 'Biaya SPP 10')
                                                {{ $cb->spp10 }} %
                                            @elseif ($item->item == 'Biaya SPP 11')
                                                {{ $cb->spp11 }} %
                                            @elseif ($item->item == 'Biaya SPP 12')
                                                {{ $cb->spp12 }} %
                                            @elseif ($item->item == 'Biaya SPP 13')
                                                {{ $cb->spp13 }} %
                                            @elseif ($item->item == 'Biaya SPP 14')
                                                {{ $cb->spp14 }} %
                                            @elseif ($item->item == 'Prakerin')
                                                {{ $cb->prakerin }} %
                                            @elseif ($item->item == 'Seminar')
                                                {{ $cb->seminar }} %
                                            @elseif ($item->item == 'Sidang')
                                                {{ $cb->sidang }} %
                                            @elseif ($item->item == 'Wisuda')
                                                {{ $cb->wisuda }} %
                                            @endif
                                        @endif
                                    </center>
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency ($biaya->daftar - ($biaya->daftar * $cb->daftar) / 100)
                                    @elseif ($item->item == 'Perlengkapan Awal')
                                        @currency ( $biaya->awal - ($biaya->awal * $cb->awal) / 100 )
                                    @elseif ($item->item == 'Dana Pengembangan')
                                        @currency ( $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 1')
                                        @currency ( $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 2')
                                        @currency ( $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 3')
                                        @currency ( $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 4')
                                        @currency ( $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 5')
                                        @currency ( $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 6')
                                        @currency ( $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 7')
                                        @currency ( $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 8')
                                        @currency ( $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 9')
                                        @currency ( $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 10')
                                        @currency ( $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 11')
                                        @currency ( $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 12')
                                        @currency ( $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 13')
                                        @currency ( $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100 )
                                    @elseif ($item->item == 'Biaya SPP 14')
                                        @currency ( $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100 )
                                    @elseif ($item->item == 'Prakerin')
                                        @currency ( $biaya->prakerin - ($biaya->prakerin * $cb->prakerin) / 100 )
                                    @elseif ($item->item == 'Seminar')
                                        @currency ( $biaya->seminar - ($biaya->seminar * $cb->seminar) / 100 )
                                    @elseif ($item->item == 'Sidang')
                                        @currency ( $biaya->sidang - ($biaya->sidang * $cb->sidang) / 100 )
                                    @elseif ($item->item == 'Wisuda')
                                        @currency ( $biaya->wisuda - ($biaya->wisuda * $cb->wisuda) / 100 )
                                    @endif
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency($sisadaftar)
                                    @elseif ($item->item == 'Perlengkapan Awal')
                                        @currency($sisaawal)
                                    @elseif ($item->item == 'Dana Pengembangan')
                                        @currency($sisadsp)
                                    @elseif ($item->item == 'Biaya SPP 1')
                                        @currency($sisaspp1)
                                    @elseif ($item->item == 'Biaya SPP 2')
                                        @currency($sisaspp2)
                                    @elseif ($item->item == 'Biaya SPP 3')
                                        @currency($sisaspp3)
                                    @elseif ($item->item == 'Biaya SPP 4')
                                        @currency($sisaspp4)
                                    @elseif ($item->item == 'Biaya SPP 5')
                                        @currency($sisaspp5)
                                    @elseif ($item->item == 'Biaya SPP 6')
                                        @currency($sisaspp6)
                                    @elseif ($item->item == 'Biaya SPP 7')
                                        @currency($sisaspp7)
                                    @elseif ($item->item == 'Biaya SPP 8')
                                        @currency($sisaspp8)
                                    @elseif ($item->item == 'Biaya SPP 9')
                                        @currency($sisaspp9)
                                    @elseif ($item->item == 'Biaya SPP 10')
                                        @currency($sisaspp10)
                                    @elseif ($item->item == 'Biaya SPP 11')
                                        @currency($sisaspp11)
                                    @elseif ($item->item == 'Biaya SPP 12')
                                        @currency($sisaspp12)
                                    @elseif ($item->item == 'Biaya SPP 13')
                                        @currency($sisaspp13)
                                    @elseif ($item->item == 'Biaya SPP 14')
                                        @currency($sisaspp14)
                                    @elseif ($item->item == 'Prakerin')
                                        @currency($sisaprakerin)
                                    @elseif ($item->item == 'Seminar')
                                        @currency($sisaseminar)
                                    @elseif ($item->item == 'Sidang')
                                        @currency($sisasidang)
                                    @elseif ($item->item == 'Wisuda')
                                        @currency ( $sisawisuda )
                                    @endif
                                </td>
                                <td align="right">
                                    @if ($item->item == 'Pendaftaran')
                                        @currency(($biaya->daftar - ($biaya->daftar * $cb->daftar) / 100) - ($sisadaftar))
                                    @elseif ($item->item == 'Perlengkapan Awal')
                                        @currency(( $biaya->awal - ($biaya->awal * $cb->awal) / 100 )-($sisaawal))
                                    @elseif ($item->item == 'Dana Pengembangan')
                                        @currency(( $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100 )-($sisadsp))
                                    @elseif ($item->item == 'Biaya SPP 1')
                                        @currency(( $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100 )-($sisaspp1))
                                    @elseif ($item->item == 'Biaya SPP 2')
                                        @currency(( $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100 )-($sisaspp2))
                                    @elseif ($item->item == 'Biaya SPP 3')
                                        @currency(( $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100 )-($sisaspp3))
                                    @elseif ($item->item == 'Biaya SPP 4')
                                        @currency(( $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100 )-($sisaspp4))
                                    @elseif ($item->item == 'Biaya SPP 5')
                                        @currency(( $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100 )-($sisaspp5))
                                    @elseif ($item->item == 'Biaya SPP 6')
                                        @currency(( $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100 )-($sisaspp6))
                                    @elseif ($item->item == 'Biaya SPP 7')
                                        @currency(( $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100 )-($sisaspp7))
                                    @elseif ($item->item == 'Biaya SPP 8')
                                        @currency(( $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100 )-($sisaspp8))
                                    @elseif ($item->item == 'Biaya SPP 9')
                                        @currency(( $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100 )-($sisaspp9))
                                    @elseif ($item->item == 'Biaya SPP 10')
                                        @currency(( $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100 )-($sisaspp10))
                                    @elseif ($item->item == 'Biaya SPP 11')
                                        @currency(( $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100 )-($sisaspp11))
                                    @elseif ($item->item == 'Biaya SPP 12')
                                        @currency(( $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100 )-($sisaspp12))
                                    @elseif ($item->item == 'Biaya SPP 13')
                                        @currency(( $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100 )-($sisaspp13))
                                    @elseif ($item->item == 'Biaya SPP 14')
                                        @currency(( $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100 )-($sisaspp14))
                                    @elseif ($item->item == 'Prakerin')
                                        @currency(( $biaya->prakerin - ($biaya->prakerin * $cb->prakerin) / 100
                                        )-($sisaprakerin))
                                    @elseif ($item->item == 'Seminar')
                                        @currency(( $biaya->seminar - ($biaya->seminar * $cb->seminar) / 100
                                        )-($sisaseminar))
                                    @elseif ($item->item == 'Sidang')
                                        @currency(( $biaya->sidang - ($biaya->sidang * $cb->sidang) / 100 )-($sisasidang))
                                    @elseif ($item->item == 'Wisuda')
                                        @currency (( $biaya->wisuda - ($biaya->wisuda * $cb->wisuda) / 100 )-( $sisawisuda
                                        ))
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
