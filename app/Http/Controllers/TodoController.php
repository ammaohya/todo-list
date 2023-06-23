<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Todo as TodoResource;
use App\Models\Todo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Validator;

class TodoController extends BaseController
{
   /**
    * @return array
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
    * @return string
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
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->content = $request->content;
        $todo->priority = $request->priority;
        $todo->save();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param Request $request
    * @param int $id
    *
    * @return string
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
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        $todo->title = $request->title;
        $todo->content = $request->content;
        $todo->priority = $request->priority;
        $todo->status = $request->status;
        $todo->save();
 
        return $this->sendResponse('ok');
    }

    /**
    * @param int $id
    *
    * @return string
    */
    public function remove($id)
    {
        $todo = Todo::find($id);

        if (is_null($todo)) {
            return $this->sendError('Todo not found.');
        }

        $todo->delete();
 
        return $this->sendResponse('ok');
    }
}
