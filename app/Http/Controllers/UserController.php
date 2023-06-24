<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
   /**
    * @return \Illuminate\Http\Response
    */
    public function listing()
    {
        $users = UserResource::collection(User::all());

        return $this->sendResponse($users);
    }

   /**
    * @return \Illuminate\Http\Response
    */
    public function getOne()
    {
        $user = User::find(Auth::id());
 
        return $this->sendResponse($user);
    }
}
