<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\Pendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class PendidikanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --------------------------------------------------------------------
        $data = [
            [
                'nama' => 'TK',
            ],
            [
                'nama' => 'SD',
            ],
            [
                'nama' => 'SMP',
            ],
            [
                'nama' => 'SMA',
            ],
            [
                'nama' => 'UNIVERSITAS',
            ],
            [
                'nama' => 'UMUM',
            ],
        ];
        // --------------------------------------------------------------------
        Pendidikan::insert($data);
        // --------------------------------------------------------------------
    }
}