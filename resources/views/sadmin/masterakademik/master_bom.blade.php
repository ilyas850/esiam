@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Master Matakuliah BOM (Bill of Makul)</h3>
                    </div>

                    <div class="box-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="4px">
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>Master Matakuliah</center>
                                    </th>
                                    <th>
                                        <center>Slave Matakuliah</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            <center>{{ $no++ }}</center>
                                        </td>
                                        <td>
                                            <center> {{ $item->makul }} </center>
                                        </td>
                                        <td>
                                            <center>
                                                @foreach ($makul as $mk)
                                                    @if ($mk->idmakul == $item->slave_idmakul)
                                                        {{ $mk->makul }}
                                                    @endif
                                                @endforeach
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


    </section>
@endsection
