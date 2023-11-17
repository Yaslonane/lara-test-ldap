<?php

namespace App\Http\Controllers;

use App\Ldap\User;
use Illuminate\Http\Request;
use LdapRecord\Models\ModelNotFoundException;
use PHPUnit\TextUI\XmlConfiguration\Php;

class MainController extends Controller
{
    public function test(){
        //return "test1";
        //echo phpinfo();
        //$connect = new User();
        //$connect->getConnection();
        /*$login = "azashchepkin";
        $user = User::getInfoUser($login);
        if (count($user)==0) {
            echo "Логина <b>".$login."</b> нет в AD";
        }else{
            echo "<pre>";
            print_r($user);
            echo "</pre>";
        }
        */
        return 1;
    }
}
