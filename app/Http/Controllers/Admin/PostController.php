<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            'title' => ['required', 'unique:posts','min:3', 'max:255'],
            'image' => ['image'],
            'content' => ['required', 'min:10'],
        ]);

        if ($request->hasFile('image')){
            $img_path = Storage::put('uploads/posts', $request['image']);
            $data['image'] = $img_path;
        }

        $data["slug"] = Str::of($data['title'])->slug('-');
        $newPost = Post::create($data);

        $newPost->slug = Str::of("$newPost->id " . $data['title'])->slug('-');
        $newPost->save();

        return redirect()->route('admin.posts.show', $newPost);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $types = Type::all();
        return view('admin.post.create', compact('types'));
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'min:3', 'max:255', Rule::unique('posts')->ignore($post->id)],
            'image' => ['image', 'max:512'],
            'content' => ['required', 'min:10'],
        ]);
        if ($request->hasFile('image')){
            Storage::delete($post->image);
            $img_path = Storage::put('uploads/posts', $request['image']);
            $data['image'] = $img_path;
        }

        $data['slug'] = Str::of("$post->id " . $data['title'])->slug('-');

        $post->update($data);

        return redirect()->route('admin.posts.show', compact('post'));
    }
    

    /**
     * Remove the specified resource from storage.
     */


    
        public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index');
    }

}