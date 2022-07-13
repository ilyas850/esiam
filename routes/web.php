<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/daftar', 'MhsController@daftar');

Route::get('login_adm', 'AdminController@login');

Route::group(['middleware' => 'sadmin'], function () {
    //master akademik
    Route::get('master_angkatan', 'SadminController@master_angkatan');
    Route::post('simpan_angkatan', 'SadminController@simpan_angkatan');
    Route::put('put_angkatan/{id}', 'SadminController@put_angkatan');
    Route::post('hapusangkatan', 'SadminController@hapusangkatan');

    //matakuliah BOM
    Route::get('master_bom', 'SadminController@master_bom');

    //penilaian prausta
    Route::get('master_penilaianprausta', 'SadminController@penilaian_prausta');
    Route::post('simpan_penilaian_prausta', 'SadminController@simpan_penilaian_prausta');
    Route::put('put_penilaian_prausta/{id}', 'SadminController@put_penilaian_prausta');
    Route::post('hapus_penilaian_prausta', 'SadminController@hapus_penilaian_prausta');

    //master kode prausta
    Route::get('master_kodeprausta', 'SadminController@master_kodeprausta');

    //master kategori prausta
    Route::get('master_kategoriprausta', 'SadminController@master_kategoriprausta');

    //master pkl
    Route::get('master_prakerin', 'SadminController@master_prakerin');
    Route::get('cek_master_prakerin/{id}', 'SadminController@cek_master_prakerin');

    //master sempro
    Route::get('master_sempro', 'SadminController@master_sempro');
    Route::get('cek_master_sempro/{id}', 'SadminController@cek_master_sempro');

    //master ta
    Route::get('master_ta', 'SadminController@master_ta');
    Route::get('cek_master_ta/{id}', 'SadminController@cek_master_ta');

    //master kategori kuisioner
    Route::get('master_kategorikuisioner', 'SadminController@master_kategorikuisioner');
    Route::post('simpan_kategori_kuisioner', 'SadminController@simpan_kategori_kuisioner');
    Route::put('put_kategori_kuisioner/{id}', 'SadminController@put_kategori_kuisioner');
    Route::post('hapus_kategori_kuisioner', 'SadminController@hapus_kategori_kuisioner');

    //master aspek kuisioner
    Route::get('master_aspekkuisioner', 'SadminController@master_aspekkuisioner');
    Route::post('simpan_aspek_kuisioner', 'SadminController@simpan_aspek_kuisioner');
    Route::put('put_aspek_kuisioner/{id}', 'SadminController@put_aspek_kuisioner');
    Route::post('hapus_aspek_kuisioner', 'SadminController@hapus_aspek_kuisioner');

    //master kuisioner
    Route::get('master_kuisioner', 'SadminController@master_kuisioner');
    Route::post('simpan_master_kuisioner', 'SadminController@simpan_master_kuisioner');
    Route::put('put_kuisioner_master/{id}', 'SadminController@put_kuisioner_master');
    Route::post('hapus_kuisioner_master', 'SadminController@hapus_kuisioner_master');

    Route::get('change_pass/{id}', 'SadminController@change');
    Route::put('pwd_adm/{id}', 'SadminController@store_new_pass');
    Route::get('show_mhs', 'SadminController@show_mhs');
    Route::get('show_user', 'SadminController@show_user');
    Route::get('usermhs/{id}', 'SadminController@add_user_mhs');
    Route::post('save_usermhs', 'SadminController@store_user_mhs');
    Route::get('show_ta', 'SadminController@show_ta');
    Route::post('save_krs_time', 'SadminController@save_krs_time');
    Route::post('delete_time_krs', 'SadminController@delete_time_krs');

    //data ipk mahasiswa aktif
    Route::get('data_ipk', 'SadminController@data_ipk');
    Route::get('export_nilai_ipk', 'SadminController@export_nilai_ipk');

    //data kaprodi
    Route::get('kaprodi', 'SadminController@kaprodi');
    Route::post('post_kaprodi', 'SadminController@post_kaprodi');
    Route::put('put_kaprodi/{id}', 'SadminController@put_kaprodi');
    Route::post('hapuskaprodi', 'SadminController@hapuskaprodi');

    Route::post('change_ta_thn', 'SadminController@change_ta_thn');
    Route::post('change_ta_tp', 'SadminController@change_ta_tp');
    Route::post('add_ta', 'SadminController@add_ta');
    Route::get('info', 'SadminController@info');
    Route::post('simpan_info', 'SadminController@simpan_info');
    Route::get('hapusinfo/{id}', 'SadminController@hapusinfo');
    Route::get('editinfo/{id}', 'SadminController@editinfo');
    Route::put('simpanedit/{id}', 'SadminController@simpanedit');
    Route::post('resetuser', 'SadminController@resetuser');
    Route::delete('hapususer/{id}', 'SadminController@hapususer');

    //EDOM
    Route::get('edom', 'EdomController@edom');
    Route::post('simpanedom', 'EdomController@simpanedom');
    Route::post('edit_time_edom', 'EdomController@edit_edom');

    Route::get('master_edom', 'EdomController@master_edom');
    Route::post('report_edom', 'EdomController@report_edom');
    Route::post('detail_edom_dosen', 'EdomController@detail_edom_dosen');
    Route::post('detail_edom_makul', 'EdomController@detail_edom_makul');

    Route::get('data_foto', 'SadminController@data_foto');
    Route::get('lihat_foto_ti', 'SadminController@lihat_foto_ti');
    Route::get('lihat_foto_tk', 'SadminController@lihat_foto_tk');
    Route::get('lihat_foto_fm', 'SadminController@lihat_foto_fm');
    Route::get('data_nilai', 'SadminController@data_nilai');
    Route::get('tes_table', 'SadminController@tes_table');
    Route::post('save_nilai_angka', 'SadminController@save_nilai_angka');
    Route::post('cek_nilai', 'SadminController@cek_nilai');
    Route::get('pembimbing', 'SadminController@pembimbing');
    Route::get('data_admin', 'SadminController@data_admin');
    Route::get('userdsn/{id}', 'SadminController@add_user_dsn');
    Route::post('saveuser_dsn', 'SadminController@saveuser_dsn');
    Route::post('resetuserdsn', 'SadminController@resetuserdsn');
    Route::delete('hapususerdsn/{id}', 'SadminController@hapususerdsn');
    Route::get('approve_krs', 'SadminController@approve_krs');
    Route::post('view_krs', 'SadminController@view_krs');
    Route::get('data_dosen_luar', 'SadminController@data_dosen_luar');
    Route::post('saveuser_dsn_luar', 'SadminController@saveuser_dsn_luar');
    Route::post('resetuserdsn_luar', 'SadminController@resetuserdsn_luar');
    Route::delete('hapususerdsn_luar/{id}', 'SadminController@hapususerdsn_luar');
    Route::get('pdm_aka', 'SadminController@pedoman');
    Route::post('save_pedoman_akademik', 'SadminController@save_pedoman_akademik');
    Route::get('data_ktm', 'SadminController@data_ktm');
    Route::post('view_ktm', 'SadminController@view_ktm');
    Route::get('downloadktm/{id}', 'SadminController@downloadktm');
    Route::get('cek_krs_admin/{id}', 'SadminController@cek_krs');
    Route::post('batalkrsmhs', 'SadminController@batalkrsmhs');

    //WADIR
    Route::get('wadir', 'SadminController@wadir');
    Route::post('post_wadir', 'SadminController@post_wadir');

    //master khs
    Route::get('nilai_khs', 'SadminController@nilai_khs');
    Route::post('export_nilai_khs', 'SadminController@export_nilai_khs');

    //master krs
    Route::get('data_krs', 'SadminController@data_krs');
    Route::post('export_krs_mhs', 'SadminController@export_krs_mhs');
    Route::get('cek_krs_mhs/{id}', 'SadminController@cek_krs_mhs');
    Route::get('batalkrs/{id}', 'SadminController@batalkrs');

    //master nilai
    Route::get('transkrip_nilai', 'SadminController@transkrip_nilai');
    Route::get('cek_transkrip/{id}', 'SadminController@cek_transkrip');
    route::post('lihat_transkrip', 'SadminController@lihat_transkrip');
    Route::get('no_transkrip', 'SadminController@no_transkrip');
    Route::get('print_transkrip/{id}', 'SadminController@print_transkrip');

    Route::get('transkrip_nilai_final', 'SadminController@transkrip_nilai_final');
    Route::get('input_transkrip_final/{id}', 'SadminController@input_transkrip_final');
    Route::post('simpan_transkrip_final', 'SadminController@simpan_transkrip_final');
    Route::get('lihat_transkrip_final/{id}', 'SadminController@lihat_transkrip_final');
    Route::get('print_transkrip_final/{id}', 'SadminController@print_transkrip_final');
    route::get('downloadAbleFile/{id}', 'SadminController@downloadAbleFile');
    Route::get('edit_transkrip_final/{id}', 'SadminController@edit_transkrip_final');
    Route::put('simpanedit_transkrip_final/{id}', 'SadminController@simpanedit_transkrip_final');

    //nilai mahasiswa
    Route::get('nilai_mhs', 'SadminController@nilai_mhs');
    Route::get('cek_nilai_mhs/{id}', 'SadminController@cek_nilai_mhs');

    //master soal
    Route::get('soal_uts', 'SadminController@soal_uts');
    Route::get('download_soal_uts/{id}', 'SadminController@download_soal_uts');
    Route::get('soal_uas', 'SadminController@soal_uas');
    Route::get('download_soal_uas/{id}', 'SadminController@download_soal_uas');

    //master perkuliahan
    Route::get('rekap_perkuliahan', 'SadminController@rekap_perkuliahan');
    Route::get('cek_rekapan/{id}', 'SadminController@cek_rekapan');
    Route::get('cek_view_bap/{id}', 'SadminController@cek_view_bap');
    Route::get('cek_print_bap/{id}', 'SadminController@cek_print_bap');
    Route::get('cek_sum_absen/{id}', 'SadminController@sum_absen');
    Route::get('print_absensi_cek/{id}', 'SadminController@print_absensi');
    Route::get('cek_jurnal_bap/{id}', 'SadminController@jurnal_bap');
    Route::get('print_jurnal_cek/{id}', 'SadminController@print_jurnal');

    //visi misi
    Route::get('visimisi', 'SadminController@visimisi');
    Route::get('add_visimisi', 'SadminController@add_visimisi');
    Route::post('save_visimisi', 'SadminController@save_visimisi');
    Route::get('editvisimisi/{id}', 'SadminController@editvisimisi');
    Route::put('saveeditvisimisi/{id}', 'SadminController@simpaneditvisimisi');

    //PraUSTA
    Route::get('data_prausta', 'SadminController@data_prausta');
    Route::post('filter_prausta', 'SadminController@filter_prausta');

    //Admin Prodi
    Route::get('data_admin_prodi', 'SadminController@data_admin_prodi');
    Route::post('post_adminprodi', 'SadminController@post_adminprodi');
    Route::put('put_adminprodi/{id}', 'SadminController@put_adminprodi');
    Route::get('hapusadminprodi/{id}', 'SadminController@hapusadminprodi');

    //Master Microsoft
    Route::get('user_microsoft', 'SadminController@user_microsoft');
    Route::post('post_microsoft_user', 'SadminController@post_microsoft_user');

    //SKPI
    Route::get('skpi', 'SadminController@skpi');

    //Kartu ujian mahasiswa
    Route::get('kartu_ujian_mhs', 'SadminController@kartu_ujian_mhs');
    Route::get('kartu_uts_mhs/{id}', 'SadminController@kartu_uts_mhs');
    Route::get('kartu_uas_mhs/{id}', 'SadminController@kartu_uas_mhs');

    //report kuisioner
    Route::get('report_kuisioner', 'SadminController@report_kuisioner');
    Route::get('report_kuisioner_kategori/{id}', 'SadminController@report_kuisioner_kategori');

    //report dosen pembimbing akademik
    Route::post('post_report_kuisioner_dsn_pa', 'SadminController@post_report_kuisioner_dsn_pa');
    Route::post('detail_kuisioner_dsn_pa', 'SadminController@detail_kuisioner_dsn_pa');
    Route::post('download_kuisioner_dsn_pa', 'SadminController@download_kuisioner_dsn_pa');
    Route::post('download_detail_kuisioner_dsn_pa', 'SadminController@download_detail_kuisioner_dsn_pa');

    //report dosen pembimbing pkl
    Route::post('post_report_kuisioner_dsn_pkl', 'SadminController@post_report_kuisioner_dsn_pkl');
    Route::post('detail_kuisioner_dsn_pkl', 'SadminController@detail_kuisioner_dsn_pkl');
    Route::post('download_kuisioner_dsn_pkl', 'SadminController@download_kuisioner_dsn_pkl');
    Route::post('download_detail_kuisioner_dsn_pkl', 'SadminController@download_detail_kuisioner_dsn_pkl');

    //report dosen pembimbing ta
    Route::post('post_report_kuisioner_dsn_ta', 'SadminController@post_report_kuisioner_dsn_ta');
    Route::post('detail_kuisioner_dsn_ta', 'SadminController@detail_kuisioner_dsn_ta');
    Route::post('download_kuisioner_dsn_ta', 'SadminController@download_kuisioner_dsn_ta');
    Route::post('download_detail_kuisioner_dsn_ta', 'SadminController@download_detail_kuisioner_dsn_ta');

    //report dosen penguji ta 1
    Route::post('post_report_kuisioner_dsn_peng1_ta', 'SadminController@post_report_kuisioner_dsn_peng1_ta');
    Route::post('detail_kuisioner_dsn_peng1_ta', 'SadminController@detail_kuisioner_dsn_peng1_ta');
    Route::post('download_kuisioner_dsn_peng1_ta', 'SadminController@download_kuisioner_dsn_peng1_ta');
    Route::post('download_detail_kuisioner_dsn_peng1_ta', 'SadminController@download_detail_kuisioner_dsn_peng1_ta');

    //report dosen penguji ta 2
    Route::post('post_report_kuisioner_dsn_peng2_ta', 'SadminController@post_report_kuisioner_dsn_peng2_ta');
    Route::post('detail_kuisioner_dsn_peng2_ta', 'SadminController@detail_kuisioner_dsn_peng2_ta');
    Route::post('download_kuisioner_dsn_peng2_ta', 'SadminController@download_kuisioner_dsn_peng2_ta');
    Route::post('download_detail_kuisioner_dsn_peng2_ta', 'SadminController@download_detail_kuisioner_dsn_peng2_ta');

    //report kuisioner baak
    Route::post('post_report_kuisioner_baak', 'SadminController@post_report_kuisioner_baak');
    Route::post('detail_kuisioner_baak', 'SadminController@detail_kuisioner_baak');
    Route::post('download_kuisioner_baak', 'SadminController@download_kuisioner_baak');
    Route::post('download_detail_kuisioner_baak', 'SadminController@download_detail_kuisioner_baak');

    //report kuisioner bauk
    Route::post('post_report_kuisioner_bauk', 'SadminController@post_report_kuisioner_bauk');
    Route::post('detail_kuisioner_bauk', 'SadminController@detail_kuisioner_bauk');
    Route::post('download_kuisioner_bauk', 'SadminController@download_kuisioner_bauk');
    Route::post('download_detail_kuisioner_bauk', 'SadminController@download_detail_kuisioner_bauk');

    //report kuisioner perpus
    Route::post('post_report_kuisioner_perpus', 'SadminController@post_report_kuisioner_perpus');
    Route::post('detail_kuisioner_perpus', 'SadminController@detail_kuisioner_perpus');
    Route::post('download_kuisioner_perpus', 'SadminController@download_kuisioner_perpus');
    Route::post('download_detail_kuisioner_perpus', 'SadminController@download_detail_kuisioner_perpus');

    //soal UTS dan UAS
    Route::get('soal_uts_uas', 'SadminController@soal_uts_uas');

    //kurikulum standar
    Route::get('master_kurikulum_standar', 'SadminController@master_kurikulum_standar');
    Route::post('lihat_kurikulum_standar', 'SadminController@lihat_kurikulum_standar');

    //master yudisium
    Route::get('master_yudisium', 'SadminController@master_yudisium');
    Route::get('validate_yudisium/{id}', 'SadminController@validate_yudisium');
    Route::get('unvalidate_yudisium/{id}', 'SadminController@unvalidate_yudisium');
    Route::put('saveedit_yudisium/{id}', 'SadminController@saveedit_yudisium');

    //master wisuda
    Route::get('master_wisuda', 'SadminController@master_wisuda');
});

Route::group(['middleware' => 'dosen'], function () {
    Route::get('mhs_bim', 'DosenController@mhs_bim');
    Route::get('record_nilai/{id}', 'DosenController@record_nilai');
    Route::get('val_krs', 'DosenController@val_krs');
    Route::get('cek_krs/{id}', 'DosenController@cek_krs');
    Route::post('hapuskrsmhs', 'DosenController@hapuskrsmhs');
    Route::post('savekrs_new', 'DosenController@savekrs_new');
    Route::post('krs_validasi', 'DosenController@krs_validasi');
    Route::get('change_pwd_dsn/{id}', 'DosenController@change');
    Route::put('pwd_dsn/{id}', 'DosenController@store_pwd_dsn');

    //matakuliah diampu dosen
    Route::get('makul_diampu_dsn', 'DosenController@makul_diampu_dsn');
    Route::get('input_kat_dsn/{id}', 'DosenController@input_kat_dsn');
    Route::post('save_nilai_KAT_dsn', 'DosenController@save_nilai_KAT_dsn');
    Route::get('input_uts_dsn/{id}', 'DosenController@input_uts_dsn');
    Route::post('save_nilai_UTS_dsn', 'DosenController@save_nilai_UTS_dsn');
    Route::get('input_uas_dsn/{id}', 'DosenController@input_uas_dsn');
    Route::post('save_nilai_UAS_dsn', 'DosenController@save_nilai_UAS_dsn');
    Route::get('input_akhir_dsn/{id}', 'DosenController@input_akhir_dsn');
    Route::post('save_nilai_AKHIR_dsn', 'DosenController@save_nilai_AKHIR_dsn');

    //histori matakuliah
    Route::get('history_makul_dsn', 'DosenController@history_makul_dsn');
    Route::get('cekmhs_dsn/{id}', 'DosenController@cekmhs_dsn');
    Route::get('cekmhs_dsn_his/{id}', 'DosenController@cekmhs_dsn_his');
    Route::post('export_xlsnilai', 'DosenController@export_xlsnilai');
    Route::get('view_bap_his/{id}', 'DosenController@view_bap_his');
    Route::get('view_history_bap/{id}', 'DosenController@view_history_bap');
    Route::get('sum_absen_his/{id}', 'DosenController@sum_absen_his');
    Route::get('jurnal_bap_his/{id}', 'DosenController@jurnal_bap_his');

    //BAP
    Route::get('entri_bap/{id}', 'DosenController@entri_bap');
    Route::get('input_bap/{id}', 'DosenController@input_bap');
    Route::post('save_bap', 'DosenController@save_bap');
    Route::get('/entri_absen/{id}', 'DosenController@entri_absen');
    Route::post('save_absensi', 'DosenController@save_absensi');
    Route::get('/edit_absen/{id}', 'DosenController@edit_absen');
    Route::post('save_edit_absensi', 'DosenController@save_edit_absensi');
    Route::get('view_bap/{id}', 'DosenController@view_bap');
    Route::get('print_bap/{id}', 'DosenController@cetak');
    Route::get('edit_bap/{id}', 'DosenController@edit_bap');
    Route::put('simpanedit_bap/{id}', 'DosenController@simpanedit_bap');
    Route::get('delete_bap/{id}', 'DosenController@delete_bap');
    Route::get('sum_absen/{id}', 'DosenController@sum_absen');
    Route::get('print_absensi/{id}', 'DosenController@print_absensi');
    Route::get('jurnal_bap/{id}', 'DosenController@jurnal_bap');
    Route::get('print_jurnal/{id}', 'DosenController@print_jurnal');

    //unduh pdf
    Route::post('unduh_pdf_nilai', 'DosenController@unduh_pdf_nilai');
    Route::get('download_absensi/{id}', 'DosenController@download_absensi');
    Route::get('download_jurnal/{id}', 'DosenController@download_jurnal');

    //PraUSTA PKL
    Route::get('pembimbing_pkl', 'DosenController@pembimbing_pkl');
    Route::get('record_bim_pkl/{id}', 'DosenController@record_bim_pkl');
    Route::get('val_bim_pkl/{id}', 'DosenController@val_bim_pkl');
    Route::post('status_judul', 'DosenController@status_judul');
    Route::get('acc_seminar_pkl/{id}', 'DosenController@acc_seminar_pkl');
    Route::get('tolak_seminar_pkl/{id}', 'DosenController@tolak_seminar_pkl');
    route::put('komentar_bimbingan/{id}', 'DosenController@komentar_bimbingan');

    //penguji PKL
    Route::get('penguji_pkl', 'DosenController@penguji_pkl');
    Route::get('isi_form_nilai_pkl/{id}', 'DosenController@isi_form_nilai_pkl');
    Route::post('simpan_nilai_prakerin', 'DosenController@simpan_nilai_prakerin');
    Route::get('edit_nilai_pkl_by_dosen_dlm/{id}', 'DosenController@edit_nilai_pkl_by_dosen_dlm');
    Route::post('put_nilai_prakerin_dosen_dlm', 'DosenController@put_nilai_prakerin_dosen_dlm');

    //PraUSTA SEMPRO
    Route::get('pembimbing_sempro', 'DosenController@pembimbing_sempro');
    Route::get('record_bim_sempro/{id}', 'DosenController@record_bim_sempro');

    //penguji SEMPRO
    Route::get('penguji_sempro', 'DosenController@penguji_sempro');
    Route::get('isi_form_nilai_proposal_dospem/{id}', 'DosenController@isi_form_nilai_proposal_dospem');
    Route::post('simpan_nilai_sempro_dospem', 'DosenController@simpan_nilai_sempro_dospem');
    Route::get('isi_form_nilai_proposal_dosji1/{id}', 'DosenController@isi_form_nilai_proposal_dosji1');
    Route::post('simpan_nilai_sempro_dosji1', 'DosenController@simpan_nilai_sempro_dosji1');
    Route::get('isi_form_nilai_proposal_dosji2/{id}', 'DosenController@isi_form_nilai_proposal_dosji2');
    Route::post('simpan_nilai_sempro_dosji2', 'DosenController@simpan_nilai_sempro_dosji2');

    Route::get('edit_nilai_sempro_by_dospem_dlm/{id}', 'DosenController@edit_nilai_sempro_by_dospem_dlm');
    Route::post('put_nilai_sempro_dospem_dlm', 'DosenController@put_nilai_sempro_dospem_dlm');
    Route::get('edit_nilai_sempro_by_dospeng1_dlm/{id}', 'DosenController@edit_nilai_sempro_by_dospeng1_dlm');
    Route::post('put_nilai_sempro_dospeng1_dlm', 'DosenController@put_nilai_sempro_dospeng1_dlm');
    Route::get('edit_nilai_sempro_by_dospeng2_dlm/{id}', 'DosenController@edit_nilai_sempro_by_dospeng2_dlm');
    Route::post('put_nilai_sempro_dospeng2_dlm', 'DosenController@put_nilai_sempro_dospeng2_dlm');

    //validasi revisi
    Route::get('validasi_dospem/{id}', 'DosenController@validasi_dospem');
    Route::get('validasi_dosji1/{id}', 'DosenController@validasi_dosji1');
    Route::get('validasi_dosji2/{id}', 'DosenController@validasi_dosji2');

    //PraUSTA TA
    Route::get('pembimbing_ta', 'DosenController@pembimbing_ta');
    Route::get('record_bim_ta/{id}', 'DosenController@record_bim_ta');

    //penguji TA
    Route::get('penguji_ta', 'DosenController@penguji_ta');
    Route::get('isi_form_nilai_ta_dospem/{id}', 'DosenController@isi_form_nilai_ta_dospem');
    Route::post('simpan_nilai_ta_dospem', 'DosenController@simpan_nilai_ta_dospem');
    Route::get('isi_form_nilai_ta_dosji1/{id}', 'DosenController@isi_form_nilai_ta_dosji1');
    Route::post('simpan_nilai_ta_dosji1', 'DosenController@simpan_nilai_ta_dosji1');
    Route::get('isi_form_nilai_ta_dosji2/{id}', 'DosenController@isi_form_nilai_ta_dosji2');
    Route::post('simpan_nilai_ta_dosji2', 'DosenController@simpan_nilai_ta_dosji2');

    Route::get('edit_nilai_ta_by_dospem_dlm/{id}', 'DosenController@edit_nilai_ta_by_dospem_dlm');
    Route::post('put_nilai_ta_dospem_dlm', 'DosenController@put_nilai_ta_dospem_dlm');
    Route::get('edit_nilai_ta_by_dospeng1_dlm/{id}', 'DosenController@edit_nilai_ta_by_dospeng1_dlm');
    Route::post('put_nilai_ta_dospeng1_dlm', 'DosenController@put_nilai_ta_dospeng1_dlm');
    Route::get('edit_nilai_ta_by_dospeng2_dlm/{id}', 'DosenController@edit_nilai_ta_by_dospeng2_dlm');
    Route::post('put_nilai_ta_dospeng2_dlm', 'DosenController@put_nilai_ta_dospeng2_dlm');

    //jadwal prausta
    Route::get('jadwal_seminar_prakerin_dlm', 'DosenController@jadwal_seminar_prakerin_dlm');
    Route::get('jadwal_seminar_proposal_dlm', 'DosenController@jadwal_seminar_proposal_dlm');
    Route::get('jadwal_sidang_ta_dlm', 'DosenController@jadwal_sidang_ta_dlm');

    //upload soal
    Route::get('upload_soal_dsn_dlm', 'DosenController@upload_soal_dsn_dlm');
    Route::post('simpan_soal_uts_dsn_dlm', 'DosenController@simpan_soal_uts_dsn_dlm');
    Route::post('simpan_soal_uas_dsn_dlm', 'DosenController@simpan_soal_uas_dsn_dlm');

    //record pembayaran
    Route::get('record_pembayaran_mhs/{id}', 'DosenController@record_pembayaran_mhs');

    //download BAP prausta
    Route::get('download_bap_pkl_dsn_dlm/{id}', 'DosenController@download_bap_pkl_dsn_dlm');
    Route::get('download_bap_sempro_dsn_dlm/{id}', 'DosenController@download_bap_sempro_dsn_dlm');
    Route::get('download_bap_ta_dsn_dlm/{id}', 'DosenController@download_bap_ta_dsn_dlm');
});

Route::group(['middleware' => 'mhs'], function () {
    Route::get('change_pwd/{id}', 'MhsController@change');
    Route::put('pwd_user/{id}', 'MhsController@store_new_pwd');
    Route::get('update/{id}', 'MhsController@update');
    Route::post('save_update', 'MhsController@store_update');
    Route::get('change/{id}', 'MhsController@change_update');
    Route::put('save_change/{id}', 'MhsController@store_change');
    Route::get('krs', 'MhsController@krs');
    Route::post('submitdata', 'MhsController@testfunction');
    Route::post('add_krs', 'MhsController@add_krs');

    Route::post('post_krs', 'MhsController@post_krs');

    Route::get('khs_mid', 'MhsController@khs_mid');
    Route::get('khs_final', 'MhsController@khs_final');
    Route::get('unduh_khs_mid', 'MhsController@unduh_khs_mid');
    Route::get('jadwal', 'MhsController@jadwal');
    Route::get('lihatabsen/{id}', 'MhsController@lihatabsen');
    Route::get('view_bap_mhs/{id}', 'MhsController@view_bap');
    Route::get('view_abs/{id}', 'MhsController@view_abs');
    Route::get('keuangan', 'MhsController@uang');
    Route::get('unduh_krs', 'MhsController@pdf_krs');
    Route::get('lihat_semua', 'MhsController@lihat_semua');
    Route::get('lihat/{id}', 'MhsController@lihat');

    // Route::get('isi_krs', 'KrsController@isi_krs');
    Route::post('simpan_krs', 'MhsController@simpan_krs');

    //isi KRS
    Route::get('isi_krs', 'KrsController@add_krs');
    Route::get('input_krs', 'KrsController@input_krs');
    Route::post('save_krs', 'KrsController@save_krs');

    Route::post('batalkrs', 'KrsController@batalkrs');
    Route::get('isi_edom', 'EdomController@isi_edom');
    Route::post('form_edom', 'EdomController@form_edom');
    Route::post('save_edom', 'EdomController@save_edom');
    Route::post('edom_kom', 'EdomController@edom_kom');
    Route::post('save_edom_kom', 'EdomController@save_com');
    Route::get('ganti_foto/{id}', 'MhsController@ganti_foto');
    Route::put('simpanfoto/{id}', 'MhsController@simpanfoto');
    Route::post('view_nilai', 'NilaiController@view_nilai');
    Route::get('nilai', 'NilaiController@nilai');
    Route::post('unduh_khs_nilai', 'NilaiController@unduh_khs_nilaipdf');
    Route::get('jdl_uts', 'MhsController@jdl_uts');
    Route::get('jdl_uas', 'MhsController@jdl_uas');
    Route::get('pedoman_akademik', 'MhsController@pedoman_akademik');
    Route::get('download/{id}', 'MhsController@download_pedoman');

    //praUSTA
    Route::get('seminar_prakerin', 'PraustaController@seminar_prakerin');
    Route::get('pengajuan_seminar_prakerin/{id}', 'PraustaController@pengajuan_seminar_prakerin');
    Route::post('simpan_ajuan_prakerin', 'PraustaController@simpan_ajuan_prakerin');
    Route::put('edit_ajuan_prakerin/{id}', 'PraustaController@edit_ajuan_prakerin');
    Route::get('ajukan_seminar_pkl/{id}', 'PraustaController@ajukan_seminar_pkl');
    Route::post('ajukan_seminar_pkl', 'PraustaController@ajukan_seminar_pkl');
    Route::put('put_prakerin/{id}', 'PraustaController@put_prakerin');

    //bimbingan prakerin
    Route::post('simpan_bimbingan', 'PraustaController@simpan_bimbingan');
    Route::put('edit_bimbingan/{id}', 'PraustaController@edit_bimbingan');

    //upload draft prakerin
    Route::post('simpan_draft_prakerin', 'PraustaController@simpan_draft_prakerin');

    //seminar proposal
    Route::get('seminar_proposal', 'PraustaController@seminar_proposal');
    Route::get('pengajuan_seminar_proposal/{id}', 'PraustaController@pengajuan_seminar_proposal');
    Route::post('simpan_ajuan_proposal', 'PraustaController@simpan_ajuan_proposal');
    Route::post('ajukan_seminar_proposal', 'PraustaController@ajukan_seminar_proposal');
    Route::get('ajukan_seminar_lagi/{id}', 'PraustaController@ajukan_seminar_lagi');
    Route::put('put_proposal/{id}', 'PraustaController@put_proposal');

    //bimbingan sempro
    Route::post('simpan_bimbingan_sempro', 'PraustaController@simpan_bimbingan_sempro');
    Route::put('edit_bimbingan_sempro/{id}', 'PraustaController@edit_bimbingan_sempro');

    //upload draft prakerin
    Route::post('simpan_draft_sempro', 'PraustaController@simpan_draft_sempro');

    //sidang ta
    Route::get('sidang_ta', 'PraustaController@sidang_ta');
    Route::get('pengajuan_sidang_ta/{id}', 'PraustaController@pengajuan_sidang_ta');
    Route::post('simpan_ajuan_ta', 'PraustaController@simpan_ajuan_ta');
    Route::post('ajukan_sidang_ta', 'PraustaController@ajukan_sidang_ta');
    Route::get('ajukan_seminar_lagi/{id}', 'PraustaController@ajukan_seminar_lagi');
    Route::put('put_ta/{id}', 'PraustaController@put_ta');

    //bimbingan sempro
    Route::post('simpan_bimbingan_ta', 'PraustaController@simpan_bimbingan_ta');
    Route::put('edit_bimbingan_ta/{id}', 'PraustaController@edit_bimbingan_ta');

    //upload draft prakerin
    Route::post('simpan_draft_ta', 'PraustaController@simpan_draft_ta');

    //upload file plagiarisme
    Route::post('simpan_file_plagiarisme', 'PraustaController@simpan_file_plagiarisme');

    //update nisn
    Route::put('put_nisn/{id}', 'MhsController@put_nisn');

    //dosen pembimbing
    Route::get('dosbing', 'MhsController@dosbing');

    //biaya kuliah
    Route::get('record_biaya', 'MhsController@record_biaya');
    Route::get('data_biaya', 'MhsController@data_biaya');

    //kuisioner
    Route::get('kuisioner', 'MhsController@kuisioner');
    Route::get('isi_dosen_pa/{id}', 'MhsController@isi_dosen_pa');
    Route::post('save_kuisioner_dsn_pa', 'MhsController@save_kuisioner_dsn_pa');
    Route::get('isi_dosen_pkl/{id}', 'MhsController@isi_dosen_pkl');
    Route::post('save_kuisioner_dsn_pkl', 'MhsController@save_kuisioner_dsn_pkl');
    Route::get('isi_dosen_ta/{id}', 'MhsController@isi_dosen_ta');
    Route::post('save_kuisioner_dsn_ta', 'MhsController@save_kuisioner_dsn_ta');
    Route::get('isi_dosen_ta_peng1/{id}', 'MhsController@isi_dosen_ta_peng1');
    Route::post('save_kuisioner_dsn_ta_peng1', 'MhsController@save_kuisioner_dsn_ta_peng1');
    Route::get('isi_dosen_ta_peng2/{id}', 'MhsController@isi_dosen_ta_peng2');
    Route::post('save_kuisioner_dsn_ta_peng2', 'MhsController@save_kuisioner_dsn_ta_peng2');
    Route::get('isi_kuis_baak/{id}', 'MhsController@isi_kuis_baak');
    Route::post('save_kuisioner_baak', 'MhsController@save_kuisioner_baak');
    Route::get('isi_kuis_bauk/{id}', 'MhsController@isi_kuis_bauk');
    Route::post('save_kuisioner_bauk', 'MhsController@save_kuisioner_bauk');
    Route::get('isi_kuis_perpus/{id}', 'MhsController@isi_kuis_perpus');
    Route::post('save_kuisioner_perpus', 'MhsController@save_kuisioner_perpus');

    //kartu ujian
    Route::get('kartu_uts', 'MhsController@kartu_uts');
    Route::get('kartu_uas', 'MhsController@kartu_uas');
    Route::get('unduh_kartu_uas', 'MhsController@unduh_kartu_uas');
    Route::get('unduh_kartu_uts', 'MhsController@unduh_kartu_uts');

    //upload sertifikat
    Route::get('upload_sertifikat', 'MhsController@upload_sertifikat');
    Route::post('post_sertifikat', 'MhsController@post_sertifikat');
    Route::put('put_sertifikat/{id}', 'MhsController@put_sertifikat');
    Route::get('hapus_sertifikat/{id}', 'MhsController@hapus_sertifikat');

    //pendaftaran yudisium
    Route::get('yudisium', 'MhsController@yudisium');
    Route::post('save_yudisium', 'MhsController@save_yudisium');
    Route::put('put_yudisium/{id}', 'MhsController@put_yudisium');

    //pendaftaran wisuda
    Route::get('wisuda', 'MhsController@wisuda');
    Route::post('save_wisuda', 'MhsController@save_wisuda');
    Route::put('put_wisuda/{id}', 'MhsController@put_wisuda');

    //download record bimbingan
    Route::get('download_bimbingan_prakerin_mhs/{id}', 'PraustaController@download_bimbingan_prakerin_mhs');
    Route::get('download_bimbingan_sempro_mhs/{id}', 'PraustaController@download_bimbingan_sempro_mhs');
    Route::get('download_bimbingan_ta_mhs/{id}', 'PraustaController@download_bimbingan_ta_mhs');
});

Route::group(['middleware' => 'nomhs'], function () {
    Route::get('pwd/{id}', 'NoMhsController@get_new_user');
    Route::put('pwd/{id}/store', 'NoMhsController@store_new_user');
});

Route::group(['middleware' => 'dosenluar'], function () {
    Route::get('makul_diampu', 'DosenluarController@makul_diampu');
    Route::get('cekmhs/{id}', 'DosenluarController@cekmhs');
    Route::get('history_makul_dsnlr', 'DosenluarController@history_makul_dsn');
    Route::get('cekmhs_dsn_hislr/{id}', 'DosenluarController@cekmhs_dsn_his');
    Route::get('val_ujian', 'DosenluarController@val_ujian');
    Route::get('input_kat/{id}', 'DosenluarController@input_kat');
    Route::post('save_nilai_KAT', 'DosenluarController@save_nilai_KAT');
    Route::get('input_uts/{id}', 'DosenluarController@input_uts');
    Route::post('save_nilai_UTS', 'DosenluarController@save_nilai_UTS');
    Route::get('input_uas/{id}', 'DosenluarController@input_uas');
    Route::post('save_nilai_UAS', 'DosenluarController@save_nilai_UAS');
    Route::get('input_akhir/{id}', 'DosenluarController@input_akhir');
    Route::post('save_nilai_AKHIR', 'DosenluarController@save_nilai_AKHIR');
    Route::get('change_pass_dsn_luar/{id}', 'DosenluarController@change_dsnluar');
    Route::put('pwd_dsn_luar/{id}', 'DosenluarController@store_pwd_dsn_luar');
    Route::post('export_xlsnilai_dsn', 'DosenluarController@export_xlsnilai');
    //BAP
    Route::get('entri_bap_dsn/{id}', 'DosenluarController@entri_bap');
    Route::get('input_bap_dsn/{id}', 'DosenluarController@input_bap');
    Route::post('save_bap_dsn', 'DosenluarController@save_bap');
    Route::get('/entri_absen_dsn/{id}', 'DosenluarController@entri_absen');
    Route::post('save_absensi_dsn', 'DosenluarController@save_absensi');
    Route::get('/edit_absen_dsn/{id}', 'DosenluarController@edit_absen');
    Route::post('save_edit_absensi_dsn', 'DosenluarController@save_edit_absensi');
    Route::get('view_bap_dsn/{id}', 'DosenluarController@view_bap');
    Route::get('print_bap_dsn/{id}', 'DosenluarController@cetak');
    Route::get('edit_bap_dsn/{id}', 'DosenluarController@edit_bap');
    Route::put('simpanedit_bap_dsn/{id}', 'DosenluarController@simpanedit_bap');
    Route::get('delete_bap_dsn/{id}', 'DosenluarController@delete_bap');
    Route::get('sum_absen_dsn/{id}', 'DosenluarController@sum_absen');
    Route::get('print_absensi_dsn/{id}', 'DosenluarController@print_absensi');
    Route::get('jurnal_bap_dsn/{id}', 'DosenluarController@jurnal_bap');
    Route::get('print_jurnal_dsn/{id}', 'DosenluarController@print_jurnal');

    //unduh pdf
    Route::post('unduh_pdf_nilai_dsn', 'DosenluarController@unduh_pdf_nilai');
    Route::get('download_absensi_dsn/{id}', 'DosenluarController@download_absensi');
    Route::get('download_jurnal_dsn/{id}', 'DosenluarController@download_jurnal');

    //histori matakuliah
    Route::get('view_bap_his_dsn/{id}', 'DosenluarController@view_bap_his');
    Route::get('view_history_bap_dsn/{id}', 'DosenluarController@view_history_bap');
    Route::get('sum_absen_his_dsn/{id}', 'DosenluarController@sum_absen_his');
    Route::get('jurnal_bap_his_dsn/{id}', 'DosenluarController@jurnal_bap_his');

    //PraUSTA PKL
    Route::get('pembimbing_pkl_dsnlr', 'DosenluarController@pembimbing_pkl');
    Route::get('record_bim_pkl_dsnlr/{id}', 'DosenluarController@record_bim_pkl');
    Route::get('val_bim_pkl_dsnlr/{id}', 'DosenluarController@val_bim_pkl');
    Route::post('status_judul_dsnlr', 'DosenluarController@status_judul');
    Route::get('acc_seminar_pkl_dsnlr/{id}', 'DosenluarController@acc_seminar_pkl');
    Route::get('tolak_seminar_pkl_dsnlr/{id}', 'DosenluarController@tolak_seminar_pkl');
    route::put('komentar_bimbingan_dsnlr/{id}', 'DosenluarController@komentar_bimbingan_dsnlr');

    //penguji PKL
    Route::get('penguji_pkl_dsnlr', 'DosenluarController@penguji_pkl');
    Route::get('isi_form_nilai_pkl_dsnlr/{id}', 'DosenluarController@isi_form_nilai_pkl');
    Route::post('simpan_nilai_prakerin_dsnlr', 'DosenluarController@simpan_nilai_prakerin');
    Route::get('edit_nilai_pkl_by_dosen_luar/{id}', 'DosenluarController@edit_nilai_pkl_by_dosen_luar');
    Route::post('put_nilai_prakerin_dosen_luar', 'DosenluarController@put_nilai_prakerin_dosen_luar');

    //PraUSTA SEMPRO
    Route::get('pembimbing_sempro_dsnlr', 'DosenluarController@pembimbing_sempro');
    Route::get('record_bim_sempro_dsnlr/{id}', 'DosenluarController@record_bim_sempro');

    //penguji SEMPRO
    Route::get('penguji_sempro_dsnlr', 'DosenluarController@penguji_sempro');
    Route::get('isi_form_nilai_proposal_dospem_dsnlr/{id}', 'DosenluarController@isi_form_nilai_proposal_dospem');
    Route::post('simpan_nilai_sempro_dospem_dsnlr', 'DosenluarController@simpan_nilai_sempro_dospem');
    Route::get('isi_form_nilai_proposal_dosji1_dsnlr/{id}', 'DosenluarController@isi_form_nilai_proposal_dosji1');
    Route::post('simpan_nilai_sempro_dosji1_dsnlr', 'DosenluarController@simpan_nilai_sempro_dosji1');
    Route::get('isi_form_nilai_proposal_dosji2_dsnlr/{id}', 'DosenluarController@isi_form_nilai_proposal_dosji2');
    Route::post('simpan_nilai_sempro_dosji2_dsnlr', 'DosenluarController@simpan_nilai_sempro_dosji2');

    Route::get('edit_nilai_sempro_by_dospem_luar/{id}', 'DosenluarController@edit_nilai_sempro_by_dospem_luar');
    Route::post('put_nilai_sempro_dospem_luar', 'DosenluarController@put_nilai_sempro_dospem_luar');
    Route::get('edit_nilai_sempro_by_dospeng1_luar/{id}', 'DosenluarController@edit_nilai_sempro_by_dospeng1_luar');
    Route::post('put_nilai_sempro_dospeng1_luar', 'DosenluarController@put_nilai_sempro_dospeng1_luar');
    Route::get('edit_nilai_sempro_by_dospeng2_luar/{id}', 'DosenluarController@edit_nilai_sempro_by_dospeng2_luar');
    Route::post('put_nilai_sempro_dospeng2_luar', 'DosenluarController@put_nilai_sempro_dospeng2_luar');

    //validasi revisi
    Route::get('validasi_dospem_dsnlr/{id}', 'DosenluarController@validasi_dospem_dsnlr');
    Route::get('validasi_dosji1_dsnlr/{id}', 'DosenluarController@validasi_dosji1_dsnlr');
    Route::get('validasi_dosji2_dsnlr/{id}', 'DosenluarController@validasi_dosji2_dsnlr');

    //PraUSTA TA
    Route::get('pembimbing_ta_dsnlr', 'DosenluarController@pembimbing_ta');
    Route::get('record_bim_ta_dsnlr/{id}', 'DosenluarController@record_bim_ta');

    //penguji TA
    Route::get('penguji_ta_dsnlr', 'DosenluarController@penguji_ta');
    Route::get('isi_form_nilai_ta_dospem_dsnlr/{id}', 'DosenluarController@isi_form_nilai_ta_dospem');
    Route::post('simpan_nilai_ta_dospem_dsnlr', 'DosenluarController@simpan_nilai_ta_dospem');
    Route::get('isi_form_nilai_ta_dosji1_dsnlr/{id}', 'DosenluarController@isi_form_nilai_ta_dosji1');
    Route::post('simpan_nilai_ta_dosji1_dsnlr', 'DosenluarController@simpan_nilai_ta_dosji1');
    Route::get('isi_form_nilai_ta_dosji2_dsnlr/{id}', 'DosenluarController@isi_form_nilai_ta_dosji2');
    Route::post('simpan_nilai_ta_dosji2_dsnlr', 'DosenluarController@simpan_nilai_ta_dosji2');

    Route::get('edit_nilai_ta_by_dospem_luar/{id}', 'DosenluarController@edit_nilai_ta_by_dospem_luar');
    Route::post('put_nilai_ta_dospem_luar', 'DosenluarController@put_nilai_ta_dospem_luar');
    Route::get('edit_nilai_ta_by_dospeng1_luar/{id}', 'DosenluarController@edit_nilai_ta_by_dospeng1_luar');
    Route::post('put_nilai_ta_dospeng1_luar', 'DosenluarController@put_nilai_ta_dospeng1_luar');
    Route::get('edit_nilai_ta_by_dospeng2_luar/{id}', 'DosenluarController@edit_nilai_ta_by_dospeng2_luar');
    Route::post('put_nilai_ta_dospeng2_luar', 'DosenluarController@put_nilai_ta_dospeng2_luar');

    //jadwal prausta
    Route::get('jadwal_seminar_prakerin_luar', 'DosenluarController@jadwal_seminar_prakerin_luar');
    Route::get('jadwal_seminar_proposal_luar', 'DosenluarController@jadwal_seminar_proposal_luar');
    Route::get('jadwal_sidang_ta_luar', 'DosenluarController@jadwal_sidang_ta_luar');

    //upload soal
    Route::get('upload_soal_dsn_luar', 'DosenluarController@upload_soal_dsn_luar');
    Route::post('simpan_soal_uts_dsn_luar', 'DosenluarController@simpan_soal_uts_dsn_luar');
    Route::post('simpan_soal_uas_dsn_luar', 'DosenluarController@simpan_soal_uas_dsn_luar');

    //download BAP prausta
    Route::get('download_bap_pkl_dsn_luar/{id}', 'DosenluarController@download_bap_pkl_dsn_luar');
    Route::get('download_bap_sempro_dsn_luar/{id}', 'DosenluarController@download_bap_sempro_dsn_luar');
    Route::get('download_bap_ta_dsn_luar/{id}', 'DosenluarController@download_bap_ta_dsn_luar');
});

Route::group(['middleware' => 'kaprodi'], function () {
    Route::get('change_pass_kaprodi/{id}', 'KaprodiController@change_pass_kaprodi');
    Route::put('pwd_kaprodi/{id}', 'KaprodiController@store_pwd_kaprodi');
    Route::get('lihat_semua_kprd', 'KaprodiController@lihat_semua_kprd');
    Route::get('lihat_kprd/{id}', 'KaprodiController@lihat_kprd');

    //master data
    Route::get('mhs_aktif', 'KaprodiController@mhs_aktif');
    Route::get('export_data_mhs', 'KaprodiController@export_data_mhs_aktif');
    Route::post('cari_mhs_aktif', 'KaprodiController@cari_mhs_aktif');
    Route::post('export_data', 'KaprodiController@export_xls_mhs_aktif');

    //data ipk
    Route::get('data_ipk_kprd', 'KaprodiController@data_ipk_kprd');
    Route::post('filter_ipk_mhs', 'KaprodiController@filter_ipk_mhs');
    Route::get('export_nilai_ipk_kprd', 'KaprodiController@export_nilai_ipk_kprd');
    Route::post('export_nilai_ipk_prodi', 'KaprodiController@export_nilai_ipk_prodi');

    //mahasiswa bimbingan
    Route::get('mhs_bim_kprd', 'KaprodiController@mhs_bim');
    Route::get('record_nilai_kprd/{id}', 'KaprodiController@record_nilai');

    //validasi krs
    Route::get('val_krs_kprd', 'KaprodiController@val_krs');
    Route::post('krs_validasi_kprd', 'KaprodiController@krs_validasi');
    Route::get('cek_krs_kprd/{id}', 'KaprodiController@cek_krs');
    Route::post('savekrs_new_kprd', 'KaprodiController@savekrs_new');
    Route::post('hapuskrsmhs_kprd', 'KaprodiController@hapuskrsmhs');

    //matakuliah diampu dosen
    Route::get('makul_diampu_kprd', 'KaprodiController@makul_diampu_dsn');
    Route::get('cekmhs_dsn_kprd/{id}', 'KaprodiController@cekmhs_dsn');
    Route::post('export_xlsnilai_kprd', 'KaprodiController@export_xlsnilai');
    Route::post('unduh_pdf_nilai_kprd', 'KaprodiController@unduh_pdf_nilai');

    //input nilai
    Route::get('input_kat_kprd/{id}', 'KaprodiController@input_kat_kprd');
    Route::post('save_nilai_KAT_kprd', 'KaprodiController@save_nilai_KAT_kprd');
    Route::get('input_uts_kprd/{id}', 'KaprodiController@input_uts_kprd');
    Route::post('save_nilai_UTS_kprd', 'KaprodiController@save_nilai_UTS_kprd');
    Route::get('input_uas_kprd/{id}', 'KaprodiController@input_uas_kprd');
    Route::post('save_nilai_UAS_kprd', 'KaprodiController@save_nilai_UAS_kprd');
    Route::get('input_akhir_kprd/{id}', 'KaprodiController@input_akhir_kprd');
    Route::post('save_nilai_AKHIR_kprd', 'KaprodiController@save_nilai_AKHIR_kprd');

    //BAP
    Route::get('entri_bap_kprd/{id}', 'KaprodiController@entri_bap');
    Route::get('input_bap_kprd/{id}', 'KaprodiController@input_bap');

    Route::get('autocomplete-search', 'KaprodiController@autocompleteSearch');

    Route::get('autocomplete', ['as' => 'autocomplete', 'uses' => 'KaprodiController@autocomplete']);

    Route::get('/cari', 'KaprodiController@loadData');

    Route::post('/autocomplete/fetch', 'KaprodiController@fetch')->name('autocomplete.fetch');

    Route::post('save_bap_kprd', 'KaprodiController@save_bap');
    //entri absen
    Route::get('/entri_absen_kprd/{id}', 'KaprodiController@entri_absen');
    Route::post('save_absensi_kprd', 'KaprodiController@save_absensi');
    Route::get('/edit_absen_kprd/{id}', 'KaprodiController@edit_absen');
    Route::post('save_edit_absensi_kprd', 'KaprodiController@save_edit_absensi');
    //lihat BAP
    Route::get('view_bap_kprd/{id}', 'KaprodiController@view_bap');
    Route::get('print_bap_kprd/{id}', 'KaprodiController@cetak');
    //edit BAP
    Route::get('edit_bap_kprd/{id}', 'KaprodiController@edit_bap');
    Route::put('simpanedit_bap_kprd/{id}', 'KaprodiController@simpanedit_bap');
    //hapus BAP
    Route::get('delete_bap_kprd/{id}', 'KaprodiController@delete_bap');
    //absensi perkuliahan
    Route::get('sum_absen_kprd/{id}', 'KaprodiController@sum_absen');
    Route::get('print_absensi_kprd/{id}', 'KaprodiController@print_absensi');
    //jurnal perkuliahan
    Route::get('jurnal_bap_kprd/{id}', 'KaprodiController@jurnal_bap');
    Route::get('print_jurnal_kprd/{id}', 'KaprodiController@print_jurnal');
    //histori matakuliah
    Route::get('history_makul_kprd', 'KaprodiController@history_makul_dsn');
    Route::get('cekmhs_dsn_his_kprd/{id}', 'KaprodiController@cekmhs_dsn_his');
    Route::get('view_bap_his_kprd/{id}', 'KaprodiController@view_bap_his');
    Route::get('sum_absen_his_kprd/{id}', 'KaprodiController@sum_absen_his');
    Route::get('print_absensi_kprd/{id}', 'KaprodiController@print_absensi');
    Route::get('jurnal_bap_his_kprd/{id}', 'KaprodiController@jurnal_bap_his');

    //nilai mahasiswa
    Route::get('nilai_mhs_kprd', 'KaprodiController@nilai_mhs_kprd');
    Route::get('cek_nilai_mhs_kprd/{id}', 'KaprodiController@cek_nilai_mhs_kprd');

    //master soal
    Route::get('soal_uts_kprd', 'KaprodiController@soal_uts_kprd');
    Route::get('soal_uas_kprd', 'KaprodiController@soal_uas_kprd');

    //cek rekapan perkuliahan
    Route::get('rekap_perkuliahan_kprd', 'KaprodiController@rekap_perkuliahan');
    Route::get('cek_rekapan_kprd/{id}', 'KaprodiController@cek_rekapan');
    Route::get('cek_view_bap_kprd/{id}', 'KaprodiController@cek_view_bap');
    Route::get('cek_print_bap_kprd/{id}', 'KaprodiController@cek_print_bap');
    Route::get('cek_sum_absen_kprd/{id}', 'KaprodiController@cek_sum_absen');
    Route::get('print_absensi_cek_kprd/{id}', 'KaprodiController@cek_print_absensi');
    Route::get('cek_jurnal_bap_kprd/{id}', 'KaprodiController@cek_jurnal_bap_kprd');
    Route::get('print_jurnal_cek_kprd/{id}', 'KaprodiController@print_jurnal_cek_kprd');

    //PraUSTA PKL
    Route::get('pembimbing_pkl_kprd', 'KaprodiController@pembimbing_pkl');
    Route::get('record_bim_pkl_kprd/{id}', 'KaprodiController@record_bim_pkl');
    Route::get('val_bim_pkl_kprd/{id}', 'KaprodiController@val_bim_pkl');
    Route::post('status_judul_kprd', 'KaprodiController@status_judul');
    Route::get('acc_seminar_pkl_kprd/{id}', 'KaprodiController@acc_seminar_pkl');
    Route::get('tolak_seminar_pkl_kprd/{id}', 'KaprodiController@tolak_seminar_pkl');
    route::put('komentar_bimbingan_kprd/{id}', 'KaprodiController@komentar_bimbingan_kprd');

    //penguji PKL
    Route::get('penguji_pkl_kprd', 'KaprodiController@penguji_pkl');
    Route::get('isi_form_nilai_pkl_kprd/{id}', 'KaprodiController@isi_form_nilai_pkl');
    Route::post('simpan_nilai_prakerin_kprd', 'KaprodiController@simpan_nilai_prakerin');
    Route::get('edit_nilai_pkl_by_dosen_kprd/{id}', 'KaprodiController@edit_nilai_pkl_by_dosen_kprd');
    Route::post('put_nilai_prakerin_dosen_kprd', 'KaprodiController@put_nilai_prakerin_dosen_kprd');

    //PraUSTA SEMPRO
    Route::get('pembimbing_sempro_kprd', 'KaprodiController@pembimbing_sempro');
    Route::get('record_bim_sempro_kprd/{id}', 'KaprodiController@record_bim_sempro');

    //penguji SEMPRO
    Route::get('penguji_sempro_kprd', 'KaprodiController@penguji_sempro');
    Route::get('isi_form_nilai_proposal_dospem_kprd/{id}', 'KaprodiController@isi_form_nilai_proposal_dospem');
    Route::post('simpan_nilai_sempro_dospem_kprd', 'KaprodiController@simpan_nilai_sempro_dospem');
    Route::get('isi_form_nilai_proposal_dosji1_kprd/{id}', 'KaprodiController@isi_form_nilai_proposal_dosji1');
    Route::post('simpan_nilai_sempro_dosji1_kprd', 'KaprodiController@simpan_nilai_sempro_dosji1');
    Route::get('isi_form_nilai_proposal_dosji2_kprd/{id}', 'KaprodiController@isi_form_nilai_proposal_dosji2');
    Route::post('simpan_nilai_sempro_dosji2_kprd', 'KaprodiController@simpan_nilai_sempro_dosji2');

    Route::get('edit_nilai_sempro_by_dospem_kprd/{id}', 'KaprodiController@edit_nilai_sempro_by_dospem_kprd');
    Route::post('put_nilai_sempro_dospem_kprd', 'KaprodiController@put_nilai_sempro_dospem_kprd');
    Route::get('edit_nilai_sempro_by_dospeng1_kprd/{id}', 'KaprodiController@edit_nilai_sempro_by_dospeng1_kprd');
    Route::post('put_nilai_sempro_dospeng1_kprd', 'KaprodiController@put_nilai_sempro_dospeng1_kprd');
    Route::get('edit_nilai_sempro_by_dospeng2_kprd/{id}', 'KaprodiController@edit_nilai_sempro_by_dospeng2_kprd');
    Route::post('put_nilai_sempro_dospeng2_kprd', 'KaprodiController@put_nilai_sempro_dospeng2_kprd');

    //validasi revisi
    Route::get('validasi_dospem_kprd/{id}', 'KaprodiController@validasi_dospem_kprd');
    Route::get('validasi_dosji1_kprd/{id}', 'KaprodiController@validasi_dosji1_kprd');
    Route::get('validasi_dosji2_kprd/{id}', 'KaprodiController@validasi_dosji2_kprd');

    //PraUSTA TA
    Route::get('pembimbing_ta_kprd', 'KaprodiController@pembimbing_ta');
    Route::get('record_bim_ta_kprd/{id}', 'KaprodiController@record_bim_ta');

    //penguji TA
    Route::get('penguji_ta_kprd', 'KaprodiController@penguji_ta');
    Route::get('isi_form_nilai_ta_dospem_kprd/{id}', 'KaprodiController@isi_form_nilai_ta_dospem');
    Route::post('simpan_nilai_ta_dospem_kprd', 'KaprodiController@simpan_nilai_ta_dospem');
    Route::get('isi_form_nilai_ta_dosji1_kprd/{id}', 'KaprodiController@isi_form_nilai_ta_dosji1');
    Route::post('simpan_nilai_ta_dosji1_kprd', 'KaprodiController@simpan_nilai_ta_dosji1');
    Route::get('isi_form_nilai_ta_dosji2_kprd/{id}', 'KaprodiController@isi_form_nilai_ta_dosji2');
    Route::post('simpan_nilai_ta_dosji2_kprd', 'KaprodiController@simpan_nilai_ta_dosji2');

    Route::get('edit_nilai_ta_by_dospem_kprd/{id}', 'KaprodiController@edit_nilai_ta_by_dospem_kprd');
    Route::post('put_nilai_ta_dospem_kprd', 'KaprodiController@put_nilai_ta_dospem_kprd');
    Route::get('edit_nilai_ta_by_dospeng1_kprd/{id}', 'KaprodiController@edit_nilai_ta_by_dospeng1_kprd');
    Route::post('put_nilai_ta_dospeng1_kprd', 'KaprodiController@put_nilai_ta_dospeng1_kprd');
    Route::get('edit_nilai_ta_by_dospeng2_kprd/{id}', 'KaprodiController@edit_nilai_ta_by_dospeng2_kprd');
    Route::post('put_nilai_ta_dospeng2_kprd', 'KaprodiController@put_nilai_ta_dospeng2_kprd');

    //monitoring prausta
    Route::get('bimbingan_prakerin', 'KaprodiController@bimbingan_prakerin');
    Route::get('detail_bim_prakerin/{id}', 'KaprodiController@detail_bim_prakerin');
    Route::post('excel_bimbingan_prakerin', 'KaprodiController@excel_bimbingan_prakerin');

    Route::get('bimbingan_sempro', 'KaprodiController@bimbingan_sempro');
    Route::get('detail_bim_sempro/{id}', 'KaprodiController@detail_bim_sempro');
    Route::post('excel_bimbingan_sempro', 'KaprodiController@excel_bimbingan_sempro');

    Route::get('bimbingan_ta', 'KaprodiController@bimbingan_ta');
    Route::get('detail_bim_ta/{id}', 'KaprodiController@detail_bim_ta');
    Route::post('excel_bimbingan_ta', 'KaprodiController@excel_bimbingan_ta');

    Route::get('nilai_prakerin_kaprodi', 'KaprodiController@nilai_prakerin_kaprodi');
    Route::get('nilai_sempro_kaprodi', 'KaprodiController@nilai_sempro_kaprodi');
    Route::get('nilai_ta_kaprodi', 'KaprodiController@nilai_ta_kaprodi');

    //jadwal prausta
    Route::get('jadwal_seminar_prakerin_kprd', 'KaprodiController@jadwal_seminar_prakerin_kprd');
    Route::get('jadwal_seminar_proposal_kprd', 'KaprodiController@jadwal_seminar_proposal_kprd');
    Route::get('jadwal_sidang_ta_kprd', 'KaprodiController@jadwal_sidang_ta_kprd');

    //upload soal
    Route::get('upload_soal_dsn_kprd', 'KaprodiController@upload_soal_dsn_kprd');
    Route::post('simpan_soal_uts_dsn_kprd', 'KaprodiController@simpan_soal_uts_dsn_kprd');
    Route::post('simpan_soal_uas_dsn_kprd', 'KaprodiController@simpan_soal_uas_dsn_kprd');

    //validasi kurikulum
    Route::get('val_kurikulum_kprd', 'KaprodiController@val_kurikulum_kprd');
    Route::post('lihat_kurikulum_standar_prodi', 'KaprodiController@lihat_kurikulum_standar_prodi');
    Route::get('add_setting_kurikulum_kprd', 'KaprodiController@add_setting_kurikulum_kprd');
    Route::post('save_setting_kurikulum_kprd', 'KaprodiController@save_setting_kurikulum_kprd');
    Route::get('edit_setting_kurikulum_kprd/{id}', 'KaprodiController@edit_setting_kurikulum_kprd');
    Route::put('put_setting_kurikulum_kprd/{id}', 'KaprodiController@put_setting_kurikulum_kprd');
    Route::get('hapus_setting_kurikulum_kprd/{id}', 'KaprodiController@hapus_setting_kurikulum_kprd');
    Route::get('aktif_setting_kurikulum_kprd/{id}', 'KaprodiController@aktif_setting_kurikulum_kprd');
    Route::get('closed_setting_kurikulum_kprd/{id}', 'KaprodiController@closed_setting_kurikulum_kprd');
    Route::get('open_setting_kurikulum_kprd/{id}', 'KaprodiController@open_setting_kurikulum_kprd');
    Route::get('validate_setting_kurikulum_kprd/{id}', 'KaprodiController@validate_setting_kurikulum_kprd');
    Route::get('unvalidate_setting_kurikulum_kprd/{id}', 'KaprodiController@unvalidate_setting_kurikulum_kprd');

    //record pembayaran
    Route::get('record_pembayaran_mhs_kprd/{id}', 'KaprodiController@record_pembayaran_mhs_kprd');

    //download BAP prausta
    Route::get('download_bap_pkl_kprd/{id}', 'KaprodiController@download_bap_pkl_kprd');
    Route::get('download_bap_sempro_kprd/{id}', 'KaprodiController@download_bap_sempro_kprd');
    Route::get('download_bap_ta_kprd/{id}', 'KaprodiController@download_bap_ta_kprd');
});

Route::group(['middleware' => 'adminprodi'], function () {
    //prakerin
    Route::get('dospem_pkl', 'ProdiController@dospem_pkl');
    Route::post('view_mhs_bim_pkl', 'ProdiController@view_mhs_bim_pkl');
    Route::post('save_dsn_bim_pkl', 'ProdiController@save_dsn_bim_pkl');
    Route::put('put_dospem_pkl/{id}', 'ProdiController@put_dospem_pkl');

    //sempro dan TA
    Route::get('dospem_sempro_ta', 'ProdiController@dospem_sempro_ta');
    Route::post('edit_dospem_sempro_ta', 'ProdiController@edit_dospem_sempro_ta');
    Route::post('view_mhs_bim_sempro_ta', 'ProdiController@view_mhs_bim_sempro_ta');
    Route::post('save_dsn_bim_sempro_ta', 'ProdiController@save_dsn_bim_sempro_ta');

    //setting standar kurikulum
    Route::get('setting_standar_kurikulum', 'ProdiController@setting_standar_kurikulum');
    Route::post('view_kurikulum_standar', 'ProdiController@view_kurikulum_standar');
    Route::get('add_setting_kurikulum', 'ProdiController@add_setting_kurikulum');
    Route::post('save_setting_kurikulum', 'ProdiController@save_setting_kurikulum');
    Route::get('edit_setting_kurikulum/{id}', 'ProdiController@edit_setting_kurikulum');
    Route::put('put_setting_kurikulum/{id}', 'ProdiController@put_setting_kurikulum');
    Route::get('hapus_setting_kurikulum/{id}', 'ProdiController@hapus_setting_kurikulum');
    Route::get('aktif_setting_kurikulum/{id}', 'ProdiController@aktif_setting_kurikulum');
    Route::get('closed_setting_kurikulum/{id}', 'ProdiController@closed_setting_kurikulum');
    Route::get('open_setting_kurikulum/{id}', 'ProdiController@open_setting_kurikulum');
});

Route::group(['middleware' => 'wadir1'], function () {
    Route::get('data_bap', 'Wadir1Controller@data_bap');
    Route::get('cek_jurnal_bap_wadir/{id}', 'Wadir1Controller@cek_jurnal_bap_wadir');

    //monitoring PRAUSTA
    Route::get('bimbingan_prakerin_wadir', 'Wadir1Controller@bimbingan_prakerin_wadir');
    Route::get('cek_bim_prakerin_wadir/{id}', 'Wadir1Controller@cek_bim_prakerin_wadir');

    Route::get('bimbingan_sempro_wadir', 'Wadir1Controller@bimbingan_sempro_wadir');
    Route::get('cek_bim_sempro_wadir/{id}', 'Wadir1Controller@cek_bim_sempro_wadir');

    Route::get('bimbingan_ta_wadir', 'Wadir1Controller@bimbingan_ta_wadir');
    Route::get('cek_bim_ta_wadir/{id}', 'Wadir1Controller@cek_bim_ta_wadir');

    Route::get('nilai_prakerin_wadir', 'Wadir1Controller@nilai_prakerin_wadir');
    Route::get('nilai_sempro_wadir', 'Wadir1Controller@nilai_sempro_wadir');
    Route::get('nilai_ta_wadir', 'Wadir1Controller@nilai_ta_wadir');
});

Route::group(['middleware' => 'prausta'], function () {
    Route::get('nilai_prausta', 'PraustaController@nilai_prausta');
    Route::post('kode_prausta', 'PraustaController@kode_prausta');
    Route::post('save_nilai_prausta', 'PraustaController@save_nilai_prausta');

    //data prakerin
    Route::get('data_prakerin', 'AdminPraustaController@data_prakerin');
    Route::get('atur_prakerin/{id}', 'AdminPraustaController@atur_prakerin');
    Route::post('simpan_atur_prakerin', 'AdminPraustaController@simpan_atur_prakerin');

    //data seminar proposal
    Route::get('data_sempro', 'AdminPraustaController@data_sempro');
    Route::get('atur_sempro/{id}', 'AdminPraustaController@atur_sempro');
    Route::post('simpan_atur_sempro', 'AdminPraustaController@simpan_atur_sempro');

    //data tugas akhir
    Route::get('data_ta', 'AdminPraustaController@data_ta');
    Route::get('atur_ta/{id}', 'AdminPraustaController@atur_ta');
    Route::post('simpan_atur_ta', 'AdminPraustaController@simpan_atur_ta');

    //record bimbingan prakerin
    Route::get('bim_prakerin', 'AdminPraustaController@bim_prakerin');
    Route::get('cek_bim_prakerin/{id}', 'AdminPraustaController@cek_bim_prakerin');

    //record bimbingan sempro
    Route::get('bim_sempro', 'AdminPraustaController@bim_sempro');
    Route::get('cek_bim_sempro/{id}', 'AdminPraustaController@cek_bim_sempro');

    //record bimbingan tugas akhir
    Route::get('bim_ta', 'AdminPraustaController@bim_ta');
    Route::get('cek_bim_ta/{id}', 'AdminPraustaController@cek_bim_ta');

    //nilai prakerin
    Route::get('nilai_prakerin', 'AdminPraustaController@nilai_prakerin');
    Route::get('edit_nilai_prakerin/{id}', 'AdminPraustaController@edit_nilai_prakerin');
    Route::post('put_nilai_prakerin', 'AdminPraustaController@put_nilai_prakerin');

    //nilai sempro
    Route::get('nilai_sempro', 'AdminPraustaController@nilai_sempro');
    Route::get('edit_nilai_sempro_bim/{id}', 'AdminPraustaController@edit_nilai_sempro_bim');
    Route::post('put_nilai_sempro_dospem', 'AdminPraustaController@put_nilai_sempro_dospem');
    Route::get('edit_nilai_sempro_p1/{id}', 'AdminPraustaController@edit_nilai_sempro_p1');
    Route::post('put_nilai_sempro_dospeng1', 'AdminPraustaController@put_nilai_sempro_dospeng1');
    Route::get('edit_nilai_sempro_p2/{id}', 'AdminPraustaController@edit_nilai_sempro_p2');
    Route::post('put_nilai_sempro_dospeng2', 'AdminPraustaController@put_nilai_sempro_dospeng2');

    //nilai tugas akhir
    Route::get('nilai_ta', 'AdminPraustaController@nilai_ta');
    Route::get('edit_nilai_ta_bim/{id}', 'AdminPraustaController@edit_nilai_ta_bim');
    Route::post('put_nilai_ta_dospem', 'AdminPraustaController@put_nilai_ta_dospem');
    Route::get('edit_nilai_ta_p1/{id}', 'AdminPraustaController@edit_nilai_ta_p1');
    Route::post('put_nilai_ta_dospeng1', 'AdminPraustaController@put_nilai_ta_dospeng1');
    Route::get('edit_nilai_ta_p2/{id}', 'AdminPraustaController@edit_nilai_ta_p2');
    Route::post('put_nilai_ta_dospeng2', 'AdminPraustaController@put_nilai_ta_dospeng2');

    //non aktifkan prausta
    Route::get('nonatifkan_prausta_prakerin/{id}', 'AdminPraustaController@nonatifkan_prausta_prakerin');
    Route::get('nonatifkan_prausta_sempro/{id}', 'AdminPraustaController@nonatifkan_prausta_sempro');
    Route::get('nonatifkan_prausta_ta/{id}', 'AdminPraustaController@nonatifkan_prausta_ta');

    //download record bimbingan
    Route::post('download_bimbingan_prakerin', 'AdminPraustaController@download_bimbingan_prakerin');
    Route::post('download_bimbingan_sempro', 'AdminPraustaController@download_bimbingan_sempro');
    Route::post('download_bimbingan_ta', 'AdminPraustaController@download_bimbingan_ta');

    //berita acara prausta
    Route::get('bap_prakerin', 'AdminPraustaController@bap_prakerin');
    Route::get('bap_sempro', 'AdminPraustaController@bap_sempro');
    Route::get('bap_ta', 'AdminPraustaController@bap_ta');

    //download BAP prausta
    Route::post('download_bap_prakerin', 'AdminPraustaController@download_bap_prakerin');
    Route::post('download_bap_sempro', 'AdminPraustaController@download_bap_sempro');
    Route::post('download_bap_ta', 'AdminPraustaController@download_bap_ta');

    //download nilai prausta
    Route::get('unduh_nilai_prakerin_b/{id}', 'AdminPraustaController@unduh_nilai_prakerin_b');
    Route::get('unduh_nilai_prakerin_c/{id}', 'AdminPraustaController@unduh_nilai_prakerin_c');
    Route::get('unduh_nilai_sempro_a/{id}', 'AdminPraustaController@unduh_nilai_sempro_a');
    Route::get('unduh_nilai_sempro_b/{id}', 'AdminPraustaController@unduh_nilai_sempro_b');
    Route::get('unduh_nilai_sempro_c/{id}', 'AdminPraustaController@unduh_nilai_sempro_c');
    Route::get('unduh_nilai_ta_a/{id}', 'AdminPraustaController@unduh_nilai_ta_a');
    Route::get('unduh_nilai_ta_b/{id}', 'AdminPraustaController@unduh_nilai_ta_b');
    Route::get('unduh_nilai_ta_c/{id}', 'AdminPraustaController@unduh_nilai_ta_c');

    //export prausta
    Route::get('export_data', 'AdminPraustaController@export_data');
    Route::post('excel_prakerin', 'AdminPraustaController@excel_prakerin');
    Route::post('excel_ta', 'AdminPraustaController@excel_ta');

    //validasi prausta
    Route::get('validate_nilai_prakerin/{id}', 'AdminPraustaController@validate_nilai_prakerin');
    Route::get('unvalidate_nilai_prakerin/{id}', 'AdminPraustaController@unvalidate_nilai_prakerin');

    Route::get('validate_nilai_sempro/{id}', 'AdminPraustaController@validate_nilai_sempro');
    Route::get('unvalidate_nilai_sempro/{id}', 'AdminPraustaController@unvalidate_nilai_sempro');

    Route::get('validate_nilai_ta/{id}', 'AdminPraustaController@validate_nilai_ta');
    Route::get('unvalidate_nilai_ta/{id}', 'AdminPraustaController@unvalidate_nilai_ta');

    //filter prausta
    Route::post('filter_prakerin_use_prodi', 'AdminPraustaController@filter_prakerin_use_prodi');
    Route::post('filter_sempro_use_prodi', 'AdminPraustaController@filter_sempro_use_prodi');
    Route::post('filter_ta_use_prodi', 'AdminPraustaController@filter_ta_use_prodi');

    Route::post('filter_bim_prakerin_use_prodi', 'AdminPraustaController@filter_bim_prakerin_use_prodi');
    Route::post('filter_bim_sempro_use_prodi', 'AdminPraustaController@filter_bim_sempro_use_prodi');
    Route::post('filter_bim_ta_use_prodi', 'AdminPraustaController@filter_bim_ta_use_prodi');

    Route::post('filter_nilai_prakerin_use_prodi', 'AdminPraustaController@filter_nilai_prakerin_use_prodi');
    Route::post('filter_nilai_sempro_use_prodi', 'AdminPraustaController@filter_nilai_sempro_use_prodi');
    Route::post('filter_nilai_ta_use_prodi', 'AdminPraustaController@filter_nilai_ta_use_prodi');

    Route::post('filter_bap_prakerin_use_prodi', 'AdminPraustaController@filter_bap_prakerin_use_prodi');
    Route::post('filter_bap_sempro_use_prodi', 'AdminPraustaController@filter_bap_sempro_use_prodi');
    Route::post('filter_bap_ta_use_prodi', 'AdminPraustaController@filter_bap_ta_use_prodi');

    //validasi prausta
    Route::get('validasi_prakerin', 'AdminPraustaController@validasi_prakerin');

    Route::get('validasi_sempro', 'AdminPraustaController@validasi_sempro');

    Route::get('validasi_ta', 'AdminPraustaController@validasi_ta');

    Route::get('validasi_akhir_prausta/{id}', 'AdminPraustaController@validasi_akhir_prausta');
    Route::get('batal_validasi_akhir_prausta/{id}', 'AdminPraustaController@batal_validasi_akhir_prausta');
});
