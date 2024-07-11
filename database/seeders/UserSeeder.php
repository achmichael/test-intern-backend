<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder{
    public function run() : void
    {
        $karyawan = new Karyawan(1, 'Michael', Hash::make('Password#123'), 'karyawan');
        $admin = new Karyawan(2, 'Ator', Hash::make('Password*123'), 'admin');
        $direktur = new Karyawan(3, 'Ilham' , Hash::make('Ilham#123'), 'direktur');
        
        DB::table('users')->insert([
            $karyawan->toArray(),
            $admin->toArray(),
            $direktur->toArray(),
        ]);
    }
}