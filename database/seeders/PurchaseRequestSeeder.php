<?php

namespace Database\Seeders;

use App\Models\Submission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseRequestSeeder extends Seeder {

    public function run () : void
    {
        $request1 = new Submission(1, 1, 'Bego', 1, 'Untuk mempemudah proses penggalian', 'pending');
        $request2 = new Submission(2, 1, 'Mobil Molen', 1, 'Untuk memudahkan mengaduk semen', 'approved_by_admin');
        $request3 = new Submission(3, 1, 'Mobil Derek', 1, 'Untuk Menderek Ketika Ada Mobil Mogok', 'approved_by_direktur');

        DB::table('purchase_requests')->insert([
            $request1->toArray(),
            $request2->toArray(),
            $request3->toArray(),
        ]);
    }
}