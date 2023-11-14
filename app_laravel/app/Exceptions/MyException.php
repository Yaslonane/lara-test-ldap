<?php

namespace App\Exceptions;

use Exception;

class MyException extends Exception{

    public function context(){
        return ['data'=>'данные'];
    }

    public function render(){
        return 'MyException';
    }
}
