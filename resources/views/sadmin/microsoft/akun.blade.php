@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data User Microsoft</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal"
                            data-target="#impodtMicrosoftUser">
                            Import Data User Microsoft
                        </button>
                    </div>
                </div>

                <div class="modal fade" id="impodtMicrosoftUser" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_microsoft_user') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Wadir</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>File excel</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama </center>
                            </th>
                            <th>
                                <center>Email</center>
                            </th>
                            <th>
                                <center>Password</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    {{ $key->nama }}
                                </td>
                                <td>
                                    {{ $key->username }}
                                </td>
                                <td>
                                    <center>{{ $key->password }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
