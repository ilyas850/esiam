@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    
    @php
        // Inisialisasi variabel untuk kalkulasi total di footer
        $grand_total_tagihan = 0;
        $grand_total_dibayar = 0;
        $grand_total_tunggakan = 0;

        // INI ADALAH KUNCI PERBAIKAN UTAMA!
        // Kamus untuk menerjemahkan nama item ke nama kolom di objek data Anda.
        // Data ini disesuaikan dengan hasil dd() Anda.
        $item_key_map = [
            'Pendaftaran' => 'daftar',
            'Perlengkapan Awal' => 'awal',
            'Dana Pengembangan' => 'dsp',
            'Biaya SPP 1' => 'spp1', 'Biaya SPP 2' => 'spp2',
            'Biaya SPP 3' => 'spp3', 'Biaya SPP 4' => 'spp4',
            'Biaya SPP 5' => 'spp5', 'Biaya SPP 6' => 'spp6',
            'Biaya SPP 7' => 'spp7', 'Biaya SPP 8' => 'spp8',
            'Biaya SPP 9' => 'spp9', 'Biaya SPP 10' => 'spp10',
            'Biaya SPP 11' => 'spp11', 'Biaya SPP 12' => 'spp12',
            'Biaya SPP 13' => 'spp13', 'Biaya SPP 14' => 'spp14',
            'Prakerin' => 'prakerin', // Asumsi nama kolomnya 'prakerin'
            'Magang 1' => 'magang1', // Menambahkan item baru dari dd()
            'Magang 2' => 'magang2', // Menambahkan item baru dari dd()
            'Seminar' => 'seminar',
            'Sidang' => 'sidang',
            'Wisuda' => 'wisuda',
        ];
    @endphp

    <section class="content">
        {{-- Box untuk Informasi Mahasiswa --}}
        <div class="box box-solid box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Detail Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="dl-horizontal">
                            <dt>Nama</dt>
                            <dd>{{ $mhs->nama }}</dd>
                            <dt>NIM</dt>
                            <dd>{{ $mhs->nim }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="dl-horizontal">
                            <dt>Program Studi</dt>
                            <dd>{{ $mhs->prodi }}</dd>
                            <dt>Kelas</dt>
                            <dd>{{ $mhs->kelas }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Box untuk Rincian Pembayaran --}}
        <div class="box box-solid box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money"></i> Rincian Pembayaran</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead style="background-color: #f7f7f7;">
                        <tr>
                            <th class="text-center" style="width: 10px;">No</th>
                            <th>Item Pembayaran</th>
                            <th class="text-right">Nominal Awal</th>
                            <th class="text-center">Beasiswa (%)</th>
                            <th class="text-right">Total Tagihan</th>
                            <th class="text-right">Telah Dibayar</th>
                            <th class="text-right">Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $item)
                            @php
                                // Ambil key kolom dari map, contoh: 'Biaya SPP 1' -> 'spp1'
                                $key = $item_key_map[$item->item] ?? null;

                                // Siapkan variabel default untuk keamanan
                                $nominal_awal = 0;
                                $persen_beasiswa = 0;
                                $harus_dibayar = 0;
                                $telah_dibayar = $item->telah_dibayar;
                                $tunggakan = 0;

                                // Lakukan kalkulasi hanya jika key ditemukan di map dan datanya ada
                                if ($key && isset($total_byr_mhs->$key)) {
                                    $nominal_awal = (float) $total_byr_mhs->$key;
                                    
                                    // Cek jika ada beasiswa dan propertinya ada
                                    $persen_beasiswa = ($detail_beasiswa && isset($detail_beasiswa->$key)) ? (float) $detail_beasiswa->$key : 0;
                                    
                                    $potongan = ($nominal_awal * $persen_beasiswa) / 100;
                                    $harus_dibayar = $nominal_awal - $potongan;
                                    $tunggakan = $harus_dibayar - $telah_dibayar;

                                    // Akumulasi untuk grand total
                                    $grand_total_tagihan += $harus_dibayar;
                                    $grand_total_dibayar += $telah_dibayar;
                                } else {
                                    // Jika item tidak terdaftar di map, tagihan dianggap 0
                                    // tapi pembayaran yang ada tetap dihitung
                                    $grand_total_dibayar += $telah_dibayar;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->item }}</td>
                                <td class="text-right">@currency($nominal_awal)</td>
                                <td class="text-center">{{ $persen_beasiswa }}%</td>
                                <td class="text-right"><strong>@currency($harus_dibayar)</strong></td>
                                <td class="text-right text-green">@currency($telah_dibayar)</td>
                                <td class="text-right">
                                    @if ($tunggakan <= 0 && $harus_dibayar > 0)
                                        <span class="badge bg-green">Lunas</span>
                                    @elseif ($harus_dibayar == 0)
                                        <span class="badge bg-gray">-</span>
                                    @else
                                        <span class="badge bg-red">@currency($tunggakan)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f0f0f0; font-weight: bold; font-size: 1.2em;">
                            <td colspan="4" class="text-right">TOTAL KESELURUHAN</td>
                            <td class="text-right">@currency($grand_total_tagihan)</td>
                            <td class="text-right text-green">@currency($grand_total_dibayar)</td>
                            <td class="text-right">
                                @php
                                    $grand_total_tunggakan = $grand_total_tagihan - $grand_total_dibayar;
                                @endphp
                                @if ($grand_total_tunggakan <= 0)
                                    <span class="badge bg-green" style="font-size: 1em;">LUNAS</span>
                                @else
                                    <span class="badge bg-red" style="font-size: 1em;">@currency($grand_total_tunggakan)</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection
