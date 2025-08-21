<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data User Mahasiswa Politeknik META Industri</h3>
                    </div>
                    
                    <div class="box-body">
                        <table id="example3" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No</center>
                                    </th>
                                    <th>Mahasiswa</th>
                                    <th>
                                        <center>Program Studi</center>
                                    </th>
                                    <th>
                                        <center>Kelas</center>
                                    </th>
                                    <th>
                                        <center>Angkatan</center>
                                    </th>
                                    <th>
                                        <center>Status</center>
                                    </th>
                                    <th>
                                        <center>Aksi</center>
                                    </th>
                                    <th>
                                        <center>Pilih</center>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no = 1; ?>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <center><?php echo e($no++); ?></center>
                                        </td>
                                        <td>
                                            <?php echo e($item->nim); ?> / <?php echo e($item->nama); ?>

                                        </td>
                                        <td>
                                            <center><?php echo e($item->prodi); ?></center>
                                        </td>
                                        <td>
                                            <center><?php echo e($item->kelas->kelas); ?></center>
                                        </td>
                                        <td>
                                            <center><?php echo e($item->angkatan->angkatan); ?></center>
                                        </td>
                                        <td>
                                            <center>
                                                <?php if(optional($item->user)->role == 3): ?>
                                                    Mahasiswa Aktif
                                                <?php elseif(optional($item->user)->role == 4): ?>
                                                    Belum Aktif
                                                <?php else: ?>
                                                    Status Tidak Diketahui
                                                <?php endif; ?>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <?php if(empty($item->user->username)): ?>
                                                    <!-- Form Individual untuk Generate User -->
                                                    <form action="<?php echo e(url('saveuser_mhs')); ?>" method="post" style="display: inline;">
                                                        <input type="hidden" name="role" value="4">
                                                        <input type="hidden" name="student" value="<?php echo e($item->idstudent); ?>">
                                                        <input type="hidden" name="username" value="<?php echo e($item->nim); ?>">
                                                        <input type="hidden" name="name" value="<?php echo e($item->nama); ?>">
                                                        <?php echo e(csrf_field()); ?>

                                                        <button type="submit" class="btn btn-info btn-xs">Generate</button>
                                                    </form>
                                                <?php elseif(!empty($item->user->username)): ?>
                                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                                        <!-- Form Reset Password -->
                                                        <form method="POST" action="<?php echo e(url('resetuser')); ?>" style="display: inline;">
                                                            <input type="hidden" name="role" value="4">
                                                            <input type="hidden" name="password" value="<?php echo e($item->user->username); ?>">
                                                            <input type="hidden" name="id" value="<?php echo e($item->user->id); ?>">
                                                            <?php echo e(csrf_field()); ?>

                                                            <button type="submit" class="btn btn-success btn-xs"
                                                                data-toggle="tooltip" data-placement="right"
                                                                title="klik untuk reset password">
                                                                <i class="fa fa-refresh"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Form Hapus User -->
                                                        <form action="/hapususer/<?php echo e($item->id_user); ?>" method="post" style="display: inline;">
                                                            <button class="btn btn-danger btn-xs" type="submit"
                                                                name="submit"
                                                                onclick="return confirm('apakah anda yakin akan menghapus user ini?')"
                                                                title="klik untuk hapus user">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <?php echo e(csrf_field()); ?>

                                                            <input type="hidden" name="_method" value="DELETE">
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </center>
                                        </td>
                                        <td align="center">
                                            <!-- Checkbox untuk multiple selection -->
                                            <input type="checkbox" name="student_checkbox[]" 
                                                   value="<?php echo e($item->idstudent); ?>" 
                                                   class="student-checkbox">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        
                        <br>
                        
                        <!-- Form untuk Multiple Generate -->
                        <form action="<?php echo e(url('save_generate_user')); ?>" method="post" id="multipleGenerateForm">
                            <?php echo e(csrf_field()); ?>

                            <!-- Hidden input container untuk selected students -->
                            <div id="selectedStudents"></div>
                            
                            <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                                class="btn btn-success">
                            <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                                class="btn btn-warning">
                            <input class="btn btn-info full-right" type="submit" name="submit" value="Generate Selected"
                                onclick="return prepareMultipleSubmit()">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByClassName('student-checkbox');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByClassName('student-checkbox');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }

        function prepareMultipleSubmit() {
            var checkboxes = document.getElementsByClassName('student-checkbox');
            var selectedStudents = document.getElementById('selectedStudents');
            var hasSelection = false;
            
            // Clear previous selections
            selectedStudents.innerHTML = '';
            
            // Add selected students as hidden inputs
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    hasSelection = true;
                    var hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'student[]';
                    hiddenInput.value = checkboxes[i].value;
                    selectedStudents.appendChild(hiddenInput);
                }
            }
            
            // Check if at least one student is selected
            if (!hasSelection) {
                alert('Pilih minimal satu mahasiswa untuk di-generate!');
                return false;
            }
            
            return true;
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/data_user.blade.php ENDPATH**/ ?>