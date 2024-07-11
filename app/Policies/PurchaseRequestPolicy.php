<?php

namespace App\Policies;

use App\Models\Karyawan;
use App\Models\PurchaseRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequestPolicy
{
    use HandlesAuthorization;

    public function requestPurchase(Karyawan $user)
    {
        return $user->role === 'karyawan';
    }

    public function approveFirst(Karyawan $user)
    {
        return $user->role === 'admin';
    }

    public function approveSecond(Karyawan $user)
    {
        return $user->role === 'direktur';
    }

    public function report (Karyawan $user){
        return $user->role === 'karyawan';
    }
}
