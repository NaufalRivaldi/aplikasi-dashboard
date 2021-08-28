<?php
// ----------------------------------------------------------------------------
namespace App\Imports;
// ----------------------------------------------------------------------------
use App\Models\VWPembayaran;
use App\Models\Cabang;
use Maatwebsite\Excel\Concerns\ToModel;
// ----------------------------------------------------------------------------
use Auth;
// ----------------------------------------------------------------------------
class LA03Import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // ------------------------------------------------------------------------
    public function model(array $row)
    {
        // --------------------------------------------------------------------
        $data   = new \stdClass; $rowArray = explode(';', str_replace('"', '', $row[0]));
        $date   = $this->date($rowArray[0]);
        // --------------------------------------------------------------------
        $data->bulan            = $date[1];
        $data->tahun            = $date[0];
        $data->type             = $rowArray[4];
        $data->nama_pembayar    = $rowArray[13];
        $data->nominal          = $rowArray[10];
        $data->cabang           = $this->kodeCabang($rowArray[0]);
        // --------------------------------------------------------------------
        return new VWPembayaran((array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
    
    // ------------------------------------------------------------------------
    public function kodeCabang($val){
        $valArray = explode('/', $val);
        $dataArray = explode('-', $valArray[0]);
        // --------------------------------------------------------------------
        return $dataArray[0];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function date($val){
        $valArray = explode('/', $val);
        // --------------------------------------------------------------------
        return [
            "20".$valArray[1],
            $valArray[2]
        ];
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------