<?php

namespace App\Http\Controllers;

//use Facade\FlareClient\View;

use App\Exceptions\MyException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        echo $request->header('host')."<br />";
        echo $request->method()."<br />";
        return "Invoke";
    }

    public function home() : string {
        return "home";
    }

    public function map() : string {
        return "map";
    }

    public function message($id){
        return "Message id:".$id;
    }

    public function testview(){
        return view('example', ['a'=>'hello', 'b'=>'World']);
        /*return view('example')
                ->with('a', 'Hello')
                ->with('b', 'world');*/
        //return View::make('example', ['a'=>'hello', 'b'=>'World']);
    }

    public function testBlade(){
        return view('testblade', ['a'=>'hello', 'b'=>'<b>Blade</b>', 'c'=>3]);
    }

    public function extendView(){
        return view('child.index');
    }

    public function testComponents(){
        return view('testcomponents');
    }

    public function testException(){
        throw new MyException();
    }

    public function testLog(){
        /*Log::debug("debug-level messages");
        Log::info("informational messages");
        Log::notice("normal but significant condition");
        Log::warning("arning conditions");
        Log::error("error conditions");
        Log::critical("critical conditions");
        Log::alert("action must be taken immediately");
        Log::emergency("system is unusable");*/


        Log::channel('daily')->info("Канал \"Daily\": informational messages. Проверка работы записи в лог");

        return 1;
    }
}
