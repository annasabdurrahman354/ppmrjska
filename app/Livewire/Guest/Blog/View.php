<?php

namespace App\Livewire\Guest\Blog;

use App\Models\Blog;
use App\Models\Kategori;
use Livewire\Attributes\Url;
use Livewire\Component;

class View extends Component
{
    public $slug = '';
    public $kategoris = [];
    public $blogs = [];

    public string $kategori_id = '';
    public string $search = '';

    public function mount($slug)
    {
        //Blog::where('slug', $slug)->increment('jumlah_pembaca');
        $this->slug = $slug;
        $this->blogs = Blog::terbit()->whereNot('slug', $this->slug)->with(['penulis', 'kategori'])->inRandomOrder()->take(3)->get();
        $this->kategoris = Kategori::orderBy('nama', 'desc')->limit(9)->get();
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

    public function find()
    {
        if($this->search != "" || $this->kategori_id != "") redirect(route('guest.blog.index', ['search' => $this->search, 'kategori_id' => $this->kategori_id]));
    }
}
