<?php

namespace App\Livewire\Guest\Blog;

use App\Models\Blog;
use App\Models\Kategori;
use Livewire\Attributes\Url;
use Livewire\Component;

class View extends Component
{
    public $slug = '';

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        #if(!$this->blog->isTerbit()) {
        #    $message = "Artikel tidak diterbitkan!";
        #    $route = route("guest.artikel.index");
        #    return view('livewire.guest.guest-error', compact('message', 'route'))->extends('layouts.guest');
        #}
        $blog = Blog::where('slug', $this->slug)->with(['penulis', 'kategori'])->firstOrFail();
        return view('livewire.guest.blog.view', compact('blog'))->layout('layouts.guest');
    }
}
