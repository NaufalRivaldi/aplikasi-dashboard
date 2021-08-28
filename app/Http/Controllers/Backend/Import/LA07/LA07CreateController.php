<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA07;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA07Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaAktifPendidikan as VWSiswaAktif; // LA07 - model
use App\Models\SiswaAktifPendidikan as SiswaAktif; // LA07 - model
use App\Models\SiswaAktifPendidikanDetail as SiswaAktifDetail; // LA07 - model
use App\Models\Cabang;
use App\Models\Pendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA07CreateController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "LA07 - Form";
        $data->siswaAktif       = new SiswaAktif();
        $data->siswaAktifDetail = [];
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";

        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        $data->pendidikans = Pendidikan::all();
        // --------------------------------------------------------------------
        return view('backend.import.la07.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Check date
    // ------------------------------------------------------------------------
    public function checkDataValidation(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            $input  = $request->all();
            $month  = Carbon::parse('01 '.$input['date'])->format('m');
            $year   = Carbon::parse('01 '.$input['date'])->format('Y');
            // ----------------------------------------------------------------
            $siswaAktif = SiswaAktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
            if(empty($siswaAktif)){
                $data->status = true;
                $data->message = "Data masih kosong, pembuatan form bisa dilakukan.";
            }else{
                $data->status = false;
                $data->message = "Data sudah terisi, mohon cek kembali pada sistem!";
            }
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            // ----------------------------------------------------------------
            $data->status = false;
            $data->message = "Data tidak valid!";
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function store(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'pendidikan_id.*'   => 'required',
            'jumlah.*'          => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create siswa aktif
            // ----------------------------------------------------------------
            $input = $request->all();
            $siswaAktif = [
                "bulan"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('m'),
                "tahun"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('Y'),
                "cabang_id" => $input['cabang_id'],
                "user_id"   => Auth::user()->id,
            ];
            // ----------------------------------------------------------------
            $mSiswaAktif = SiswaAktif::create($siswaAktif);
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Create siswa aktif detail
            // ----------------------------------------------------------------
            if(count($input['pendidikan_id']) > 0){
                for($i = 0; $i < count($input['pendidikan_id']); $i++){
                    $siswaAktifDetail = [
                        "pendidikan_id"             => $input['pendidikan_id'][$i],
                        "jumlah"                    => $input['jumlah'][$i],
                        "siswa_aktif_pendidikan_id" => $mSiswaAktif->id,
                    ];
                    // --------------------------------------------------------
                    SiswaAktifDetail::create($siswaAktifDetail);
                    // --------------------------------------------------------
                }
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.la07.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la07.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function edit($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "LA07 - Edit Form";
        $data->siswaAktif       = SiswaAktif::find($id);
        $data->siswaAktifDetail = SiswaAktifDetail::where('siswa_aktif_pendidikan_id', $id)->get();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "edit";
        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        $data->pendidikans = Pendidikan::all();
        // --------------------------------------------------------------------
        return view('backend.import.la07.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function update(Request $request, $id)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'id'                => 'required',
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'pendidikan_id.*'       => 'required',
            'jumlah.*'          => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Update siswa aktif
            // ----------------------------------------------------------------
            $input = $request->all();
            // ----------------------------------------------------------------
            $siswaAktif = SiswaAktif::find($input['id']);
            $siswaAktif->bulan      = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $siswaAktif->tahun      = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            $siswaAktif->cabang_id  = $input['cabang_id'];
            $siswaAktif->user_id    = Auth::user()->id;
            $siswaAktif->save();
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Create pembayaran detail
            // ----------------------------------------------------------------
            SiswaAktifDetail::where('siswa_aktif_pendidikan_id', $input['id'])->delete();
            if(count($input['pendidikan_id']) > 0){
                for($i = 0; $i < count($input['pendidikan_id']); $i++){
                    $siswaAktifDetail = [
                        "pendidikan_id"             => $input['pendidikan_id'][$i],
                        "jumlah"                    => $input['jumlah'][$i],
                        "siswa_aktif_pendidikan_id" => $input['id'],
                    ];
                    // --------------------------------------------------------
                    SiswaAktifDetail::create($siswaAktifDetail);
                    // --------------------------------------------------------
                }
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.la07.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la07.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------