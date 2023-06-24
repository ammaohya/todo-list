<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Todo as TodoResource;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Validator;

class TodoController extends BaseController
{
   /**
    * @return \Illuminate\Http\Response
    */
   public function listing()
   {
       $todos = TodoResource::collection(Todo::orderBy('created_at', 'desc')
       ->get());

       return $this->sendResponse($todos);
   }

   /**
    * @param Request $request
    *
    * @return \Illuminate\Http\Response
    */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'priority' => [
                'required',
                Rule::in(['High', 'Middle', 'Low']),
            ],
            'assign_user_id' => 'nullable|int',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->content = $request->content;
        $todo->priority = $request->priority;
        if (!is_null($request->assign_user_id)) {
            $user = User::find($request->assign_user_id);
            if (is_null($user)) {
                return $this->sendError('Assign user not found.');
            }
            $todo->assign_user_id = $user->id;
        }
        $todo->save();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param Request $request
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'priority' => [
                'required',
                Rule::in(['High', 'Middle', 'Low']),
            ],
            'status' => [
                'required',
                Rule::in(['New', 'Ongoing', 'Pending', 'Finished']),
            ],
            'assign_user_id' => 'nullable|int',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        if ($todo->lock_operator_id && $todo->lock_operator_id != Auth::id()) {
            return $this->sendError('Locked operator and operator not the same person.');
        }

        $todo->title = $request->title;
        $todo->content = $request->content;
        $todo->priority = $request->priority;
        $todo->status = $request->status;
        if (is_null($request->assign_user_id)) {
            $todo->assign_user_id = null;
        } else {
            $user = User::find($request->assign_user_id);
            if (is_null($user)) {
                return $this->sendError('Assign user not found.');
            }
            $todo->assign_user_id = $user->id;
        }
        $todo->save();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function remove($id)
    {
        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        if ($todo->lock_operator_id && $todo->lock_operator_id != Auth::id()) {
            return $this->sendError('Locked operator and operator not the same person.');
        }

        $todo->delete();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function lock($id)
    {
        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        if (!is_null($todo->lock_operator_id)) {
            return $this->sendError('Todo already locked.');
        }

        $todo->lock_operator_id = Auth::id();
        $todo->save();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function unlock($id)
    {
        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        if (is_null($todo->lock_operator_id)) {
            return $this->sendError('Todo already unlocked.');
        }

        if ($todo->lock_operator_id && $todo->lock_operator_id != Auth::id()) {
            return $this->sendError('Locked operator and operator not the same person.');
        }

        $todo->lock_operator_id = null;
        $todo->save();
 
        return $this->sendResponse('ok');
    }
}
