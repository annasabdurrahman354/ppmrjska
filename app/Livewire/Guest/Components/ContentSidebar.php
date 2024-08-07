<?php

namespace App\Livewire\Guest\Components;

use App\Models\Blog;
use App\Models\Kategori;
use Livewire\Component;

class ContentSidebar extends Component
{
    public $kategoris = [];
    public $blogs = [];

    public string $kategori_id = '';
    public string $search = '';

    public function find()
    {
        if($this->search != "" || $this->kategori_id != "") redirect(route('guest.blog.index', ['search' => $this->search, 'kategori_id' => $this->kategori_id]));
    }

    public function mount($slug)
    {
        $this->blogs = Blog::terbit()->whereNot('slug', $slug)->with(['penulis', 'kategori'])->inRandomOrder()->take(3)->get();
        $this->kategoris = Kategori::orderBy('nama', 'desc')->limit(9)->get();
    }

    public function render()
    {
        return view('livewire.guest.components.content-sidebar');
    }
}
