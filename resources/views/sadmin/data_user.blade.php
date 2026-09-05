@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Data User Mahasiswa Politeknik META Industri</h3>
                    </div>
                    
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example3" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="40px">No</th>
                                        <th>Mahasiswa</th>
                                        <th class="text-center">Program Studi</th>
                                        <th class="text-center">Kelas</th>
                                        <th class="text-center">Angkatan</th>
                                        <th class="text-center" width="130px">Status</th>
                                        <th class="text-center" width="90px">Aksi</th>
                                        <th class="text-center" width="40px">
                                            <input type="checkbox" id="checkAllHeader" title="Pilih / Batalkan Semua">
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $item->nim }}</strong><br>
                                                <span>{{ $item->nama }}</span>
                                            </td>
                                            <td class="text-center">{{ $item->prodi ?? '-' }}</td>
                                            <td class="text-center">{{ $item->kelas->kelas ?? '-' }}</td>
                                            <td class="text-center">{{ $item->angkatan->angkatan ?? '-' }}</td>
                                            <td class="text-center">
                                                @if (optional($item->user)->role == 3)
                                                    <span class="label label-success">Mahasiswa Aktif</span>
                                                @elseif (optional($item->user)->role == 4)
                                                    <span class="label label-warning">Belum Aktif</span>
                                                @else
                                                    <span class="label label-default">Belum Ada Akun</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (empty($item->user->username))
                                                    <!-- Form Individual untuk Generate User -->
                                                    <form action="{{ url('saveuser_mhs') }}" method="post" style="display: inline;">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="role" value="4">
                                                        <input type="hidden" name="student" value="{{ $item->idstudent }}">
                                                        <input type="hidden" name="username" value="{{ $item->nim }}">
                                                        <input type="hidden" name="name" value="{{ $item->nama }}">
                                                        <button type="submit" class="btn btn-info btn-xs" data-toggle="tooltip" title="Generate Akun">
                                                            <i class="fa fa-user-plus"></i> Generate
                                                        </button>
                                                    </form>
                                                @else
                                                    <div style="display: inline-flex; gap: 4px; justify-content: center; align-items: center;">
                                                        <!-- Form Reset Password -->
                                                        <form method="POST" action="{{ url('resetuser') }}" style="display: inline;">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="role" value="4">
                                                            <input type="hidden" name="password" value="{{ $item->user->username }}">
                                                            <input type="hidden" name="id" value="{{ $item->user->id }}">
                                                            <button type="submit" class="btn btn-success btn-xs"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Reset password ke NIM default"
                                                                onclick="return confirm('Apakah Anda yakin ingin me-reset password akun ini ke NIM?')">
                                                                <i class="fa fa-refresh"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Form Hapus User -->
                                                        <form action="{{ url('hapususer/' . $item->idstudent) }}" method="post" style="display: inline;">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button class="btn btn-danger btn-xs" type="submit"
                                                                onclick="return confirm('Apakah Anda yakin akan menghapus user akun mahasiswa ini?')"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Hapus user akun">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <!-- Checkbox untuk multiple selection -->
                                                <input type="checkbox" name="student_checkbox[]" 
                                                       value="{{ $item->idstudent }}" 
                                                       class="student-checkbox">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <hr style="margin: 15px 0;">
                        
                        <!-- Form untuk Multiple Generate -->
                        <form action="{{ url('save_generate_user') }}" method="post" id="multipleGenerateForm">
                            {{ csrf_field() }}
                            <!-- Hidden input container untuk selected students -->
                            <div id="selectedStudents"></div>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" onclick="check_all()">
                                    <i class="fa fa-check-square-o"></i> Tandai Semua
                                </button>
                                <button type="button" class="btn btn-default btn-sm" onclick="uncheck_all()">
                                    <i class="fa fa-square-o"></i> Hilangkan Semua Tanda
                                </button>
                            </div>
                            
                            <button class="btn btn-primary btn-sm pull-right" type="submit" name="submit"
                                onclick="return prepareMultipleSubmit()">
                                <i class="fa fa-users"></i> Generate Selected
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        function check_all() {
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#example3')) {
                var table = $('#example3').DataTable();
                table.$('.student-checkbox').prop('checked', true);
            } else {
                var chk = document.getElementsByClassName('student-checkbox');
                for (var i = 0; i < chk.length; i++) {
                    chk[i].checked = true;
                }
            }
            var headerChk = document.getElementById('checkAllHeader');
            if (headerChk) headerChk.checked = true;
        }

        function uncheck_all() {
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#example3')) {
                var table = $('#example3').DataTable();
                table.$('.student-checkbox').prop('checked', false);
            } else {
                var chk = document.getElementsByClassName('student-checkbox');
                for (var i = 0; i < chk.length; i++) {
                    chk[i].checked = false;
                }
            }
            var headerChk = document.getElementById('checkAllHeader');
            if (headerChk) headerChk.checked = false;
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery) {
                // Header checkbox toggle
                $('#checkAllHeader').on('change', function() {
                    var isChecked = this.checked;
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example3')) {
                        var table = $('#example3').DataTable();
                        table.$('.student-checkbox').prop('checked', isChecked);
                    } else {
                        $('.student-checkbox').prop('checked', isChecked);
                    }
                });

                // Re-sync header checkbox when any row checkbox changes
                $(document).on('change', '.student-checkbox', function() {
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example3')) {
                        var table = $('#example3').DataTable();
                        var total = table.$('.student-checkbox').length;
                        var checked = table.$('.student-checkbox:checked').length;
                        $('#checkAllHeader').prop('checked', total > 0 && total === checked);
                    }
                });
            }
        });

        function prepareMultipleSubmit() {
            var selectedStudents = document.getElementById('selectedStudents');
            selectedStudents.innerHTML = '';
            var count = 0;

            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#example3')) {
                var table = $('#example3').DataTable();
                table.$('.student-checkbox:checked').each(function() {
                    count++;
                    var hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'student[]';
                    hiddenInput.value = $(this).val();
                    selectedStudents.appendChild(hiddenInput);
                });
            } else {
                var checkboxes = document.getElementsByClassName('student-checkbox');
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        count++;
                        var hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'student[]';
                        hiddenInput.value = checkboxes[i].value;
                        selectedStudents.appendChild(hiddenInput);
                    }
                }
            }

            if (count === 0) {
                alert('Pilih minimal satu mahasiswa untuk di-generate!');
                return false;
            }

            return confirm('Apakah Anda yakin ingin men-generate ' + count + ' user mahasiswa terpilih?');
        }
    </script>
@endsection