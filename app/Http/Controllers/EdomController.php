<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Models\Dosen;
use App\Models\Student;
use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Kurikulum_periode;
use App\Models\Periode_tahun;
use App\Models\Periode_tipe;
use App\Models\Waktu_edom;
use App\Models\Edom_master;
use App\Models\Edom_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EdomController extends Controller
{
  public function edom()
  {
    $tahun = Periode_tahun::where('status', 'ACTIVE')->get();

    $tipe = Periode_tipe::where('status', 'ACTIVE')->get();

    // Mengambil baris data terakhir tanpa perlu me-load seluruh data dan loop kosong
    $keyedom = Waktu_edom::orderBy('id', 'desc')->first();

    $ldate = date('m/d/Y');

    return view('sadmin/edom', ['now' => $ldate, 'edom' => $keyedom, 'tahun' => $tahun, 'tipe' => $tipe]);
  }

  public function simpanedom(Request $request)
  {
    $cektgl = strtotime($request->waktu_akhir);
    $cektglawal = strtotime('now');

    if ($cektgl < $cektglawal) {

      Alert::error('maaf waktu salah', 'maaf');
      return redirect()->back();
    } else {
      $id = $request->id;
      $time_nya = Waktu_edom::find($id);
      $time_nya->waktu_awal = $request->waktu_awal;
      $time_nya->waktu_akhir = $request->waktu_akhir;
      $time_nya->status = $request->status;
      $time_nya->save();

      Alert::success('Pembukaan Edom', 'Berhasil')->autoclose(3500);
      return redirect()->back();
    }
  }

  public function edit_edom(Request $request)
  {
    $this->validate($request, [
      'status' => 'required',
      'id' => 'required',
    ]);

    $id = $request->id;
    $edom = Waktu_edom::find($id);
    $edom->waktu_awal = $request->waktu_awal;
    $edom->waktu_akhir = $request->waktu_akhir;
    $edom->status = $request->status;
    $edom->save();

    Alert::success('Penutupan Edom', 'Berhasil');
    return redirect('edom');
  }

  public function isi_edom()
  {
    $waktuEdom = Waktu_edom::orderBy('id', 'desc')->first();

    if (!$waktuEdom || $waktuEdom->status != 1) {
      alert()->error('Pengisian EDOM Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    }

    $studentId = Auth::user()->id_user;
    $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

    if (!$tahun || !$tipe) {
      alert()->warning('Periode akademik aktif belum tersedia', 'EDOM belum dapat ditampilkan');
      return redirect('home');
    }

    $edomCourses = DB::table('student_record')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->leftJoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->where('student_record.id_student', $studentId)
      ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
      ->where('student_record.status', 'TAKEN')
      ->select(
        'matakuliah.makul',
        'matakuliah.kode',
        'kurikulum_periode.id_makul',
        'kurikulum_periode.id_dosen',
        'student_record.id_student',
        DB::raw('MAX(student_record.id_kurtrans) as id_kurtrans'),
        DB::raw('MAX(student_record.id_kurperiode) as id_kurperiode'),
        'dosen.nama'
      )
      ->groupBy('matakuliah.makul', 'matakuliah.kode', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student', 'dosen.nama')
      ->get();

    $transactions = Edom_transaction::where('id_student', $studentId)
      ->whereIn('id_kurperiode', $edomCourses->pluck('id_kurperiode')->all())
      ->get(['id_edom', 'nilai_edom', 'id_kurperiode', 'id_kurtrans']);
    $submissions = $this->splitEdomSubmissionKeys($transactions);

    $edom = $this->decorateEdomCourses($edomCourses, $submissions['form'], $submissions['comment']);
    $edomSummary = [
      'total' => $edom->count(),
      'completed' => $edom->where('is_completed', true)->count(),
      'remaining' => $edom->where('is_completed', false)->count(),
    ];

    return view('mhs/edom/isi_edom', compact('edom', 'edomSummary'));
  }

  protected function decorateEdomCourses($courses, array $formSubmissionKeys, array $commentSubmissionKeys)
  {
    $formSubmissionKeys = array_flip($formSubmissionKeys);
    $commentSubmissionKeys = array_flip($commentSubmissionKeys);

    return $courses->map(function ($course) use ($formSubmissionKeys, $commentSubmissionKeys) {
      $key = $course->id_kurperiode . ':' . $course->id_kurtrans;
      $course->form_completed = array_key_exists($key, $formSubmissionKeys);
      $course->comment_completed = array_key_exists($key, $commentSubmissionKeys);
      $course->is_completed = $course->form_completed;

      return $course;
    });
  }

  /**
   * ID 17 is used by the legacy comment form. A numeric value (1-4) stored
   * under this ID is a historical form answer, not a submitted comment.
   */
  protected function commentEdomId()
  {
    return 17;
  }

  protected function splitEdomSubmissionKeys($transactions)
  {
    $formSubmissionKeys = [];
    $commentSubmissionKeys = [];

    foreach ($transactions as $transaction) {
      $key = $transaction->id_kurperiode . ':' . $transaction->id_kurtrans;
      $value = trim((string) $transaction->nilai_edom);
      $isNumericFormAnswer = in_array($value, ['1', '2', '3', '4'], true);
      $isComment = (int) $transaction->id_edom === $this->commentEdomId() && !$isNumericFormAnswer;

      if ($isComment) {
        $commentSubmissionKeys[] = $key;
      } else {
        $formSubmissionKeys[] = $key;
      }
    }

    return ['form' => array_values(array_unique($formSubmissionKeys)), 'comment' => array_values(array_unique($commentSubmissionKeys))];
  }

  public function form_edom(Request $request)
  {
    $id = Auth::user()->id_user;
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;

    $cekedom = Edom_transaction::where('id_student', $id)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->whereIn('nilai_edom', ['1', '2', '3', '4'])
      ->exists();

    if ($cekedom) {
      Alert::warning('maaf edom mata kuliah isi sudah diisi', 'MAAF !!');
      return redirect('isi_edom');
    } else {

      $makul = Matakuliah::where('idmakul', $mk)->first();

      if ($dsn == 0) {
        $dosen = '';

        $nama_dsn = '';
        $akademik = '';
      } else {
        $dosen = Dosen::where('iddosen', $dsn)->first();
        $nama_dsn = $dosen->nama;
        $akademik = $dosen->akademik;
      }

      $edom = Edom_master::orderBy('type', 'ASC')
        ->where('status', 'ACTIVE')
        ->where('id_edom', '<>', $this->commentEdomId())
        ->orderBy('description', 'ASC')
        ->get();
      $edomQuestionCount = $edom->count();

      return view('mhs/edom/form_edom', compact('edom', 'edomQuestionCount', 'akademik', 'nama_dsn', 'makul', 'mk', 'kurtr', 'kurper') + ['ids' => $id]);
    }
  }

  public function save_edom(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
      'nilai_edom' => 'required|array',
      'nilai_edom.*' => 'required',
    ]);
    $studentId = Auth::user()->id_user;
    $mhs = Student::where('idstudent', $studentId)->first();
    $answers = $this->normalizeEdomAnswers($request->input('nilai_edom', []));
    $expectedAnswerCount = Edom_master::where('status', 'ACTIVE')
      ->where('id_edom', '<>', $this->commentEdomId())
      ->count();

    if (count($answers) !== $expectedAnswerCount) {
      Alert::error('Mohon lengkapi seluruh pertanyaan EDOM sebelum menyimpan.', 'Jawaban belum lengkap');
      return redirect()->back()->withInput();
    }

    $nama = $mhs->nama;
    $nama_ok = str_replace("'", "", $nama);

    $cekedom = Edom_transaction::where('id_student', $studentId)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->whereIn('nilai_edom', ['1', '2', '3', '4'])
      ->exists();

    if ($cekedom) {
      Alert::warning('maaf edom mata kuliah sudah dipilih', 'MAAF !!');
      return redirect('isi_edom');
    } else {
      DB::transaction(function () use ($answers, $studentId, $request, $nama_ok) {
        foreach ($answers as $nilai) {
          $edom = explode(',', $nilai, 2);
          $idom = $edom[0];
          $nidom = $edom[1];

          $isi = new Edom_transaction;
          $isi->id_edom = $idom;
          $isi->id_student = $studentId;
          $isi->id_kurperiode = $request->id_kurperiode;
          $isi->id_kurtrans = $request->id_kurtrans;
          $isi->nilai_edom = $nidom;
          $isi->created_by = $nama_ok;
          $isi->created_date = date('Y-m-d H:i:s');
          $isi->save();
        }
      });
    }

    Alert::success('', 'Pengisian EDOM anda berhasil ')->autoclose(3500);
    return redirect('isi_edom');
  }

  protected function normalizeEdomAnswers(array $answers)
  {
    return array_values(array_filter($answers, function ($answer) {
      return $answer !== null && $answer !== '';
    }));
  }

  protected function normalizeEdomComment($comment)
  {
    return trim((string) $comment);
  }

  public function edom_kom(Request $request)
  {
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;
    $makul = Matakuliah::where('idmakul', $mk)->first();
    $dosen = Dosen::where('iddosen', $dsn)->first();

    return view('mhs/edom/komentar', ['dsn' => $dsn, 'dosen' => $dosen, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper]);
  }

  public function save_com(Request $request)
  {
    $this->validate($request, [
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
      'nilai_edom' => 'required|string|max:1000',
    ]);
    $studentId = Auth::user()->id_user;
    $comment = $this->normalizeEdomComment($request->input('nilai_edom'));

    if ($comment === '') {
      return redirect()->back()
        ->withInput()
        ->withErrors(['nilai_edom' => 'Komentar tidak boleh kosong.']);
    }

    $student = Student::where('idstudent', $studentId)->first();

    if (!$student) {
      Alert::error('Data mahasiswa tidak ditemukan.', 'Komentar belum tersimpan');
      return redirect('isi_edom');
    }

    $nama_ok = str_replace("'", "", $student->nama);

    $cekedom = Edom_transaction::where('id_edom', $this->commentEdomId())
      ->where('id_student', $studentId)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->whereNotIn('nilai_edom', ['1', '2', '3', '4'])
      ->exists();

    if ($cekedom) {

      Alert::warning('maaf komentar di edom mata kuliah ini sudah diisi', 'MAAF !!');
      return redirect('isi_edom');
    }

    DB::transaction(function () use ($studentId, $request, $comment, $nama_ok) {
      $isi = new Edom_transaction;
      $isi->id_edom = $this->commentEdomId();
      $isi->id_student = $studentId;
      $isi->id_kurperiode = $request->id_kurperiode;
      $isi->id_kurtrans = $request->id_kurtrans;
      $isi->nilai_edom = $comment;
      $isi->created_by = $nama_ok;
      $isi->created_date = date('Y-m-d H:i:s');
      $isi->save();
    });

    Alert::success('', 'Komentar EDOM berhasil disimpan.')->autoclose(3500);
    return redirect('isi_edom');
  }

  public function isi_edom_new()
  {
    $waktu_edom = Waktu_edom::all();
    foreach ($waktu_edom as $edom) {
      // code...
    }

    if ($edom->status == 1) {

      $id = Auth::user()->id_user;

      $thn = Periode_tahun::where('status', 'ACTIVE')->first();

      $tp = Periode_tipe::where('status', 'ACTIVE')->first();

      $latestPosts = DB::table('student_record')
        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
        ->where('student_record.id_student', $id)
        ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
        ->where('student_record.status', 'TAKEN')
        ->select(
          'matakuliah.makul',
          'matakuliah.kode',
          'kurikulum_periode.id_makul',
          'kurikulum_periode.id_dosen',
          'student_record.id_student',
          DB::raw('MAX(student_record.id_kurtrans) as id_kurtrans'),
          DB::raw('MAX(student_record.id_kurperiode) as id_kurperiode'),
          'dosen.nama'
        )
        ->groupBy('matakuliah.makul', 'matakuliah.kode', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student', 'dosen.nama')
        ->get();

      return view('mhs/edom_new/isi_edom', ['edom' => $latestPosts]);
    } else {

      alert()->error('Pengisian EDOM Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    }
  }

  public function form_edom_new(Request $request)
  {
    $id = $request->id_student;
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;

    $cekedom = Edom_transaction::where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {
      Alert::warning('maaf edom mata kuliah isi sudah diisi', 'MAAF !!');
      return redirect('isi_edom_new');
    } elseif (count($cekedom) == 0) {

      $makul = Matakuliah::where('idmakul', $mk)->first();

      if ($dsn == 0) {
        $dosen = '';

        $nama_dsn = '';
        $akademik = '';
      } else {
        $dosen = Dosen::where('iddosen', $dsn)->first();
        $nama_dsn = $dosen->nama;
        $akademik = $dosen->akademik;
      }

      $edm = Edom_master::where('id_edom', 1)->get();

      foreach ($edm as $keydm) {
        // code...
      }

      $edom = Edom_master::orderBy('type', 'ASC')
        ->where('status', 'ACTIVE')
        ->orderBy('description', 'ASC')
        ->paginate(30);

      return view('mhs/edom_new/form_edom', ['keydm' => $keydm, 'edom' => $edom, 'akademik' => $akademik, 'nama_dsn' => $nama_dsn, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper, 'ids' => $id]);
    }
  }

  public function save_edom_new(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
      'nilai_edom' => 'required',
    ]);
    $mhs = Student::where('idstudent', $request->id_student)->first();

    $nama = $mhs->nama;
    $nama_ok = str_replace("'", "", $nama);

    $cekedom = Edom_transaction::where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {
      Alert::warning('maaf edom mata kuliah sudah dipilih', 'MAAF !!');
      return redirect('isi_edom_new');
    } elseif (count($cekedom) == 0) {
      $jml = count($request->nilai_edom);
      for ($i = 0; $i < $jml; $i++) {
        $nilai = $request->nilai_edom[$i];
        $edom = explode(',', $nilai, 2);
        $idom = $edom[0];
        $nidom = $edom[1];

        $isi = new Edom_transaction;
        $isi->id_edom = $idom;
        $isi->id_student = $request->id_student;
        $isi->id_kurperiode = $request->id_kurperiode;
        $isi->id_kurtrans = $request->id_kurtrans;
        $isi->nilai_edom = $nidom;

        $isi->created_by = $nama_ok;
        $isi->created_date   = date("Y-m-d h:i:s");
        $isi->save();
      }
    }

    Alert::success('', 'Pengisian EDOM anda berhasil ')->autoclose(3500);
    return redirect('isi_edom_new');
  }

  public function edom_kom_new(Request $request)
  {
    $id = $request->id_student;
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;
    $makul = Matakuliah::where('idmakul', $mk)->first();
    $dosen = Dosen::where('iddosen', $dsn)->first();

    $edom_com = Edom_master::orderBy('id_edom', 'DESC')
      ->paginate(1);

    return view('mhs/edom_new/komentar', ['edom_com' => $edom_com, 'dsn' => $dsn, 'dosen' => $dosen, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper, 'ids' => $id]);
  }

  public function save_com_new(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
    ]);

    $name = Student::where('idstudent', $request->id_student)->get();
    foreach ($name as $value) {
      // code...
    }

    $nama = $value->nama;
    $nama_ok = str_replace("'", "", $nama);

    $cekedom = Edom_transaction::where('id_edom', $request->id_edom)
      ->where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {

      Alert::warning('maaf komentar di edom mata kuliah ini sudah diisi', 'MAAF !!');
      return redirect('isi_edom_new');
    } else {
      $isi = new Edom_transaction;
      $isi->id_edom = $request->id_edom;
      $isi->id_student = $request->id_student;
      $isi->id_kurperiode = $request->id_kurperiode;
      $isi->id_kurtrans = $request->id_kurtrans;
      $isi->nilai_edom = $request->nilai_edom;

      $isi->created_by = $nama_ok;
      $isi->created_date   = date("Y-m-d h:i:s");
      $isi->save();
      Alert::success('', 'Pengisian Komentar di EDOM anda berhasil ')->autoclose(3500);
      return redirect('isi_edom_new');
    }
  }

  public function master_edom()
  {
    $periodetahun = Periode_tahun::orderBy('id_periodetahun', 'DESC')->get();
    $periodetipe = Periode_tipe::orderBy('id_periodetipe', 'DESC')->get();
    $prodi = Prodi::select('kodeprodi', 'prodi')
      ->groupBy('kodeprodi', 'prodi')
      ->orderBy('kodeprodi', 'DESC')
      ->get();

    return view('sadmin/edom/master_edom', compact('periodetahun', 'periodetipe', 'prodi'));
  }

  public function report_edom(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $idprodi = $request->kodeprodi;
    $tipe = $request->tipe_laporan;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
    $prodii = Prodi::where('kodeprodi', $idprodi)->first();

    $thn = $periodetahun->periode_tahun;
    $tp = $periodetipe->periode_tipe;
    $prd = $prodii->prodi;

    if ($tipe == 'by_makul') {

      if ($idperiodetahun == 6 && $idperiodetipe == 1) {
        $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
      } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
        $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
      } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
        $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
      } elseif ($idperiodetahun < 6) {
        $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
      } elseif ($idperiodetahun > 6) {
        $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
      }

      // $data = DB::select('CALL edom_by_makul_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));

      return view('sadmin/edom/report_edom_by_makul', compact('data', 'thn', 'tp', 'prd', 'idperiodetahun', 'idperiodetipe', 'idprodi'));
    } elseif ($tipe == 'by_dosen') {

      $data = DB::select('CALL edom_by_dosen_new(?,?)', array($idperiodetahun, $idperiodetipe));

      return view('sadmin/edom/report_edom_by_dosen', compact('data', 'thn', 'tp', 'idperiodetahun', 'idperiodetipe'));
    }
  }

  public function detail_edom_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $iddosen = $request->id_dosen;
    $periodetahun = $request->periodetahun;
    $periodetipe = $request->periodetipe;
    $nama = $request->nama;

    $useOldProcedure = ($idperiodetahun < 6) || ($idperiodetahun == 6 && in_array($idperiodetipe, [1, 2]));

    $procedureName = $useOldProcedure ? 'detail_edom_dosen_old' : 'detail_edom_dosen_new';

    $data = DB::select("CALL {$procedureName}(?,?,?)", [$idperiodetahun, $idperiodetipe, $iddosen]);

    return view('sadmin/edom/detail_edom_dosen', compact('data', 'nama', 'periodetahun', 'periodetipe'));
  }

  public function detail_edom_makul(Request $request)
  {
    $idkurperiode = $request->id_kurperiode;
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    if ($idperiodetahun == 6 && $idperiodetipe == 1) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
      $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
    } elseif ($idperiodetahun < 6) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun > 6) {
      $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
    }


    $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
      ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul')
      ->first();

    return view('sadmin/edom/detail_edom_makul', compact('data', 'data_mk'));
  }

  //download report edom PDF
  public function download_report_edom_by_makul(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $idprodi = $request->kodeprodi;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
    $prodi = Prodi::where('kodeprodi', $idprodi)->first();

    $thn = $periodetahun->periode_tahun;
    $ganti = str_replace('/', '_', $thn);
    $tp = $periodetipe->periode_tipe;
    $prd = $prodi->prodi;

    $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));

    $pdf = PDF::loadView('sadmin/edom/pdf_report_edom_makul', compact('data', 'thn', 'tp', 'prd'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Matakuliah' . ' ' . $ganti . ' ' . $tp . ' ' . $prd . '.pdf');
  }

  #download report edom dosen PDF
  public function download_report_edom_by_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

    $thn = $periodetahun->periode_tahun;
    $tp = $periodetipe->periode_tipe;

    $data = DB::select('CALL edom_by_dosen_new(?,?)', array($idperiodetahun, $idperiodetipe));

    $pdf = PDF::loadView('sadmin/edom/pdf_report_edom_dosen', compact('data', 'thn', 'tp'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Dosen' . ' ' . $thn . ' ' . $tp . '.pdf');
  }

  //download detail edom
  public function download_detail_edom_makul(Request $request)
  {
    $idkurperiode = $request->id_kurperiode;
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    if ($idperiodetahun == 6 && $idperiodetipe == 1) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
      $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
    } elseif ($idperiodetahun < 6) {
      $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
    } elseif ($idperiodetahun > 6) {
      $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
    }

    $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
      ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $thn = $data_mk->periode_tahun;
    $tp = $data_mk->periode_tipe;
    $nama_mk = $data_mk->makul;
    $nama_dsn = $data_mk->nama;
    $nama_kls = $data_mk->kelas;
    $nama_prd = $data_mk->prodi;

    $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_makul', compact('data', 'thn', 'tp', 'nama_prd', 'nama_dsn', 'nama_mk', 'nama_kls'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Matakuliah' . ' ' . $nama_mk . ' ' . $nama_kls . '.pdf');
  }

  public function download_detail_edom_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $iddosen = $request->id_dosen;
    $periodetahun = $request->periodetahun;
    $periodetipe = $request->periodetipe;
    $nama = $request->nama;

    if ($idperiodetahun == 6 && $idperiodetipe == 1) {
      $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
      $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
    } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
      $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
    } elseif ($idperiodetahun < 6) {
      $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
    } elseif ($idperiodetahun > 6) {
      $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
    }

    $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_dosen', compact('data', 'periodetahun', 'periodetipe', 'nama'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Dosen' . ' ' . $nama . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
  }
}
