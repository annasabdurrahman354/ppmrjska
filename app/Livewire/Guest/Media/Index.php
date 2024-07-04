<?php

namespace App\Livewire\Guest\Media;

use App\Enums\StatusBlog;
use App\Enums\SumberMedia;
use App\Models\Blog;
use App\Models\Carousel;
use App\Models\Kategori;
use App\Models\Media;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    public $kategoris = [];

    #[Url]
    public string $kategori_id = '';
    #[Url]
    public string $search = '';
    public $clickedMedia = [];

    public function setFilter(){
        redirect()->to(route('guest.media.index', [
            'kategori_id' => $this->kategori_id,
            'search' => $this->search
        ]));
    }

    public function setClickedMedia($clickedMedia)
    {
        $this->clickedMedia = $clickedMedia;
        if ($this->clickedMedia['sumber'] === SumberMedia::TIKTOK->value) {
            $this->dispatch('media-tiktok-clicked');
        }
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
        $medias = Media::with(['pengunggah', 'kategori']);
        if($this->kategori_id != ""){
            $medias =  $medias->where('kategori_id', $this->kategori_id);
        }
        if($this->search != ""){
            $medias =  $medias->where('judul', 'like', '%' . $this->search . '%' );
        }
        $medias = $medias->paginate(10);

        return view('livewire.guest.media.index', compact('medias'))->layout('layouts.guest');
    }
}
