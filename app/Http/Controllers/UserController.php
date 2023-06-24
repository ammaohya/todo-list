<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
   /**
    * @return \Illuminate\Http\Response
    */
    public function getOne()
    {
        $user = User::find(Auth::id());
 
        return $this->sendResponse($user);
    }
}
