<?php

namespace App\Livewire\Guest\Blog;

use App\Enums\StatusBlog;
use App\Models\Blog;
use App\Models\Carousel;
use App\Models\Kategori;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    public $kategoris = [];

    #[Url]
    public string $kategori_id = '';
    #[Url]
    public string $search = '';

    public function setFilter(){
        redirect()->to(route('guest.blog.index', [
            'kategori_id' => $this->kategori_id,
            'search' => $this->search
        ]));
    }

    public function resetFilter(){
        redirect()->to(route('guest.blog.index'));
    }

    public function mount()
    {
        $this->kategoris = Kategori::get()->map(function($kategori){
            return [
                'id' => $kategori['id'],
                'nama' => $kategori['nama'],
            ];
        });

        if(request()->kategori_id){
            $this->kategori_id = request()->kategori_id;
        }

        if(request()->search){
            $this->search = request()->search;
        }
    }

    public function render()
    {
        $blogs = Blog::with(['penulis', 'kategori'])->terbit();
        if($this->kategori_id != ""){
            $blogs =  $blogs->where('kategori_id', $this->kategori_id);
        }
        if($this->search != ""){
            $blogs =  $blogs->where('judul', 'like', '%' . $this->search . '%' );
        }
        $blogs = $blogs->paginate(10);

        return view('livewire.guest.blog.index', compact('blogs'))->layout('layouts.guest');
    }
}
