<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $types = Type::all();
        return view('admin.posts.create', compact('types'));
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
        ]
    
    );
        $data["slug"] = Str::of($data['title'])->slug('-');

        if ($request->hasFile('image')){
            $img_path = Storage::put('uploads/posts', $request->file('image'));
            $data['image'] = $img_path;
        }

        
        $newPost = Post::create($data);
        return redirect()->route('admin.posts.show', $newPost);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $types = Type::all();
        return view('admin.posts.edit', compact('post','types'));
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Post $post)
        {
            //
            // dd($request->all());

            $data = $request->validate(
                
                [
                    'title' => ['required', 'max:255', Rule::unique('posts')->ignore($post->id)],
                    'content' => ['required', ''],
                    'image' => ['image', 'max:512'],
                ]
                    
            );
            
            $img_path = Storage::put('uploads/posts', $request['image']);
            $data['image'] = $img_path;
            $data['slug'] = Str::of($data['title'])->slug('-');
            $post->update($data);
    
            return redirect()->route('admin.posts.show', compact('post'));
            //
        }
    /**
     * Remove the specified resource from storage.
     */


    
        public function destroy(Post $post)
    {
        //
        $post->delete();
        return redirect()->route('admin.posts.index');
        // dd($post);
    }

    public function deletedIndex(Post $post)
    {
        $posts = Post::onlyTrashed()->paginate(10);
        return view('admin.posts.deleted', compact('posts'));
    }

    public function restore(string $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return redirect()->route('admin.posts.index', $id);
    }

    public function obliterate(string $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        Storage::delete($post->image);
        $post->forceDelete();
        return redirect()->route('admin.posts.index');
    }
}

