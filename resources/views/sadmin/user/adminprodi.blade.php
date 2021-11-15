@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Data Admin Prodi Politeknik META Industri</h3>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-xs-2">
                <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addpsi">
                    <i class="fa fa-plus"></i> Input Data Admin Prodi
                </button>
            </div>
        </div>
        <br>
        <div class="modal fade" id="addpsi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" >
                <form method="post" action="{{url('post_adminprodi')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="role" value="9">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Admin Prodi</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Staff</label>
                                <select class="form-control" name="id_user" required>
                                  <option>-pilih-</option>
                                  @foreach ($staff as $keystf)
                                    <option value="{{$keystf->idstaff}},{{$keystf->nama}}">{{$keystf->nama}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                              <label>Username</label>
                              <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                              <label>Password</label>
                              <input type="text" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="4px"><center>No</center></th>
              <th><center>Nama Staff</center></th>
              <th><center>Username</center></th>
              <th><center>Aksi</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($data as $key)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td><center>{{$key->nama}}</center></td>
                <td><center>{{$key->username}}</center></td>
                <td><center>
                  <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUpdateAdminprodi{{ $key->id }}" title="klik untuk edit"><i class="fa fa-edit"></i></button>
                  <a href="/hapusadminprodi/{{ $key->id }}" class="btn btn-danger btn-xs" onclick="return confirm('apakah anda yakin akan menghapus user ini?')"><i class="fa fa-trash"></i></a>
                  {{-- <form action="/hapususer/{{ $key->id }}" method="post">
                    <button class="btn btn-danger btn-xs" title="klik untuk hapus" type="submit" name="submit" onclick="return confirm('apakah anda yakin akan menghapus user ini?')">Hapus</button>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                  </form> --}}
                </center></td>
              </tr>

              <div class="modal fade" id="modalUpdateAdminprodi{{ $key->id }}" tabindex="-1" aria-labelledby="modalUpdateAdminprodi" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title">Update Kaprodi</h5>
                          </div>
                          <div class="modal-body">
                            <form action="/put_adminprodi/{{ $key->id }}" method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="updated_by" value="{{Auth::user()->name}}">
                                <div class="form-group">
                                    <label>Nama Staff</label>
                                    <select class="form-control" name="id_user">
                                      <option value="{{$key->idstaff}},{{$key->nama}}">{{$key->nama}}</option>
                                      @foreach ($staff as $keystf)
                                        <option value="{{$keystf->idstaff}},{{$keystf->nama}}">{{$keystf->nama}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                  <label>Username</label>
                                  <input type="text" name="username" class="form-control" value="{{$key->username}}">
                                </div>
                            <button type="submit" class="btn btn-primary">Perbarui Data</button>
                            </form>
                          </div>
                      </div>
                  </div>
              </div>


            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
