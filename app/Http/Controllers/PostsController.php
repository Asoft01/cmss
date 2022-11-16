<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\Post\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Posts\CreatePostsRequest;
use App\Post;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index')->with('posts', Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories', Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {
        // Upload the image to storage 
        // dd($request->image);
        // dd($request->image->store('posts'));
        $image = $request->image->store('posts');
        // create the post 
        Post::create([
            'title' => $request->title, 
            'description' => $request->description,
            'content' => $request->content, 
            'image' => $image, 
            'published_at' => $request->published_at, 
            'category_id' => $request->category
        ]);
        // flash message 
        session()->flash('success', 'Post created successfully');
        // redirect the user 
        return redirect(route('posts.index'));
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
    public function edit(Post $post)
    {
        return view('posts.create')->with('post', $post)->with('categories', Category::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {   
        $data = $request->only(['title', 'description', 'published_at', 'content']); 

        // check if new image 

        if($request->hasFile('image')){
            // Upload it

            $image = $request->image->store('posts'); 

            // delete old one 

            // Storage::delete($post->image); 

            $post->deleteImage();


            $data['image'] = $image;
            
        }

        // update attributes 

        $post->update($data); 
        
        // flash messages 

        session()->flash('success', 'Post updated successfully'); 

        // redirect user 
        return redirect(route('posts.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $post->delete();
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();

        if($post->trashed()){
            // Storage::delete($post->image);
            $post->deleteImage();
            $post->forceDelete();
        }else{
            $post->delete();
        }

        session()->flash('success', 'Post trashed successfully');
        return redirect(route('posts.index'));
    }

    /**
     * Display a list of all trashed post.
     *
     * @return \Illuminate\Http\Response
     */

     public function trashed(){
        // $trashed = Post::withTrashed()->get();
        $trashed = Post::onlyTrashed()->get();

        return view('posts.index')->with('posts', $trashed);
     }

     public function restore($id){
        // public function restore(Post $post){
        $post = Post::withTrashed()->where('id', $id)->firstOrFail(); 
        $post->restore();

        session()->flash('success', 'Post restored successfully'); 

        return redirect()->back(); 
     }
}
