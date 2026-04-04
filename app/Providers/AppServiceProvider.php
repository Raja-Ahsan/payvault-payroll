<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        // View::composer('layouts.web.partials.header', function ($view) {
        //     $view->with('cartCount', Cart::current()->items->sum('qty'));
        // });
        // View::composer('*', function ($view) {
        //     $cartCount = 0;
        //     if (auth()->check()) {
        //         $cart = Cart::where('user_id', auth()->id())->with('items')->first();
        //     } else {
        //         $cart = Cart::where('session_id', session()->getId())->with('items')->first();
        //     }

        //     if ($cart) {
        //         $cartCount = $cart->items->sum('qty');
        //     }

        //     $view->with('cartCount', $cartCount);
        // });
    }
}
