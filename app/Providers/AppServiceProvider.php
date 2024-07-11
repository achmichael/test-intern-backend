<?php

namespace App\Providers;

use App\Models\PurchaseRequest;
use App\Policies\PurchaseRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        PurchaseRequest::class => PurchaseRequestPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('request-purchase', 'App\Policies\PurchaseRequestPolicy@requestPurchase');
        Gate::define('approve-first', 'App\Policies\PurchaseRequestPolicy@approveFirst');
        Gate::define('approve-second', 'App\Policies\PurchaseRequestPolicy@approveSecond');
        Gate::define('report', 'App\Policies\PurchaseRequestPolicy@report');
    }
}
