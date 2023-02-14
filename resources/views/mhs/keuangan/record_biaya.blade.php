@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Record Biaya Kuliah</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No </center>
                                    </th>
                                    <th>
                                        <center>Item Bayar</center>
                                    </th>
                                    <th>
                                        <center>Tanggal Bayar</center>
                                    </th>
                                    <th>
                                        <center>Nomor Kuitansi</center>
                                    </th>
                                    <th>
                                        <center>Nominal Bayar</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($kuitansi as $key)
                                    <tr>
                                        <td align="center">{{ $no++ }}</td>
                                        <td>
                                            {{ $key->item }}

                                        </td>
                                        <td align="center">
                                            {{ Carbon\Carbon::parse($key->tanggal)->formatLocalized('%d %B %Y') }}
                                        </td>
                                        <td align="center">{{ $key->nokuit }}</td>
                                        <td align="right"> @currency((float) $key->bayar)</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td align="center" colspan="4"><b>Total Bayar</b></td>
                                    <td align="right"><b>@currency($totalbayarmhs)</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
