<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $title;
    public $body;
    public $image;
    public $postId = null;
    public $newImage;

    public $showModalForm = false;

    public function banner(string $message, string $style = 'success')
    {
        request()->session()->flash('flash.banner', $message);
        request()->session()->flash('flash.bannerStyle', $style);
    }

    public function showCreatePostModal()
    {
        $this->reset();
        $this->showModalForm = true;
    }

    public function storePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image|max:1024'
        ]);

        $image_name = $this->image->getClientOriginalName();
        $this->image->storeAs('public/photos', $image_name);
        Post::create([
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'body' => $this->body,
            'image' => $image_name,
            'slug' => Str::slug($this->title)
        ]);
        $this->reset();
        session()->flash('flash.banner', 'Post Stored Successfully');
        session()->flash('flash.bannerStyle', 'success');
        return $this->redirect('/');
    }

    public function showEditPostModal($id)
    {
        $this->reset();
        $this->postId = $id;
        $this->showModalForm = true;
        $this->loadEditForm();
    }

    public function loadEditForm()
    {
        $post = Post::findOrFail($this->postId);
        $this->title = $post->title;
        $this->body = $post->body;
        $this->newImage = $post->image;
    }

    public function updatePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image|max:1024|nullable'
        ]);

        if ($this->image) {
            Storage::delete('public/photos/' . $this->newImage);
            $this->newImage = $this->image->getClientOriginalName();
            $this->image->storeAs('public/photos', $this->newImage);
        }

        Post::find($this->postId)->update([
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->newImage,
        ]);
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        Storage::delete('public/photos/' . $post->image);
        $post->delete();
        $this->banner('Post Deleted Successfully');
    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => Post::orderByDesc('created_at')->paginate(5)
        ]);
    }
}
