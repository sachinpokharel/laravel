<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\post;
class postController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    } 
    public function index()
    {
        $posts=post::all();
        return view('back.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|alpha|min:5',

            'status' => 'required|boolean',
            'files' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
           //Handle file upload
        if ($request->hasFile('files')) {
            // Get jst ext
            $extension = $request->file('files')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time() . '.' . $extension;
            //uplod image
            $file = $request->file('files');
            $destinationPath = public_path('/uploads');
            $file->move($destinationPath, $fileNameToStore);
        } else {
            return redirect()->back()->withInput($request->input())->with('error', 'File Not Selected');
        }
        $post= new post([
            'title'=>$request->get('title'), //right side is table data name and left side is form name
            'description'=>$request->get('description'),
            'status'=>$request->get('status'),
            'image'=>$fileNameToStore
            
        ]);

       
        $post->save();
      return redirect('/posts')->with('success', 'post has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = post::find($id);

        return view('back.posts.edit', compact('posts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'description'=> 'required',
            'status' => 'required|integer'
          ]);
    
          $post = Category::find($id);
          $post->title = $request->get('title');
          $post->description = $request->get('description');
          $post->status = $request->get('status');
          $post->save();
    
          return redirect('/posts')->with('success', 'post has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $post = post::find($id);
    $post->delete();

     return redirect('/posts')->with('success', 'post has been deleted Successfully');
    }
}



