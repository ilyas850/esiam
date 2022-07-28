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
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th style="width: 10px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Item Pembayaran</center>
                            </th>
                            <th>
                                <center>Nominal dibayar</center>
                            </th>
                            <th>
                                <center>SKS Teori/Praktek</center>
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
                                    @currency($item->telah_dibayar)
                                </td>
                                <td>
                                    <center></center>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
