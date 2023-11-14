<?php

namespace App\Providers;

use Facade\FlareClient\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //View::share('Global_data', 'Global value');
        view()->share('Global_data', 'Global value');

        Blade::directive('importantmessage', function ($param){
            return "<?php echo '<b>$param</b>'; ?>";
        });
    }


}
