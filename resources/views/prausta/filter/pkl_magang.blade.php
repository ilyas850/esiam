@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter PKL/Magang</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_pkl_magang') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Pilih PKL/Magang</label>
                            <select class="form-control" name="filter" required>
                                <option></option>
                                <option value="PKL">PKL</option>
                                <option value="Magang">Magang</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Tampilkan</button>
                </div>
            </form>
        </div>
    </section>
@endsection
