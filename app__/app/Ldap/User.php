<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];

    public static function getInfoUser($login){
        //$user = User::where('mailNickname', '=', $login)->get();
        $user = User::where('samaccountname', '=', $login)->get();
        //echo $user[0]['displayname'][0];
        //return '<img src="data:image/png;base64,'.base64_encode($user[0]['thumbnailphoto'][0]).'" max-height="360px" class="img-rounded">';
        return $user;
    }
}
