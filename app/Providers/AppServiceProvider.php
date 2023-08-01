<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    private $models = [
        '\App\Models\Contestant',
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // foreach ($this->models as $modelClass)
        // {   
        //     $modelClass::creating(function($model) {
        //         $user = Auth::user();
        //         $model->created_by = $user->ID_user;
        //     });
        // }
    }
}
