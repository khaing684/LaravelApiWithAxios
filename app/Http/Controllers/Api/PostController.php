<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts =Post::all();

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $messages = ['required' => 'The :attribute field is required.',];
       
    $validator =Validator::make($request->all(),[
        'title'=> 'required',
        'description'=> 'required',
        ],$messages);

        if($validator->passes()){
            $posts =Post::create([
            'title'=> $request->title,
            'description'=>$request->description,
        ]);

       return response()->json(['post'=> $posts,'msg'=> 'created is succefully']);
        }else{
             return response()->json(['msg'=> $validator->errors() ]);
        }
        }
        
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $posts = Post::find($id);

        return response()->json($posts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $posts =Post::findOrFail($id);

        $posts->update([
            'title'=> $request->title,
            'description'=> $request->description
        ]);

        return response()->json(['msg'=> 'update successfully',
        'post'=> $posts ], 200
    );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $posts =Post::find($id);
        $posts->delete();
         return response()->json(['deletedPost'=>$posts,'msg'=> 'delete successfully']);
    }
}
