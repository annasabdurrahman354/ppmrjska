<?php

namespace App\Livewire\Guest;

use App\Enums\SumberMedia;
use App\Models\Blog;
use App\Models\Carousel;
use App\Models\DewanGuru;
use App\Models\Media;
use Livewire\Component;

class Index extends Component
{
    public $clickedMedia = [];

    public function setClickedMedia($clickedMedia)
    {
        $this->clickedMedia = $clickedMedia;
        if ($this->clickedMedia['sumber'] === SumberMedia::TIKTOK->value) {
            $this->dispatch('media-tiktok-clicked');
        }
    }

    public function render()
    {
        $carousels = Carousel::where('status_aktif', true)->get();
        $latest_blogs = Blog::terbit()
            ->latest('created_at')
            ->with(['penulis', 'kategori'])
            ->take(2)
            ->get();
        $latest_medias = Media::latest('created_at')
            ->with(['pengunggah', 'kategori'])
            ->take(8)
            ->get();
        $dewan_gurus = DewanGuru::get();
        $latest_blogs_ids = $latest_blogs->pluck('id');
        $another_blogs = Blog::terbit()
            ->whereNotIn('id', $latest_blogs_ids)
            ->latest('created_at')
            ->with(['penulis', 'kategori'])
            ->take(3)
            ->get();



        return view('livewire.guest.index', compact('carousels', 'latest_medias','latest_blogs', 'dewan_gurus', 'latest_blogs_ids','another_blogs'))->layout('layouts.guest');
    }
}
