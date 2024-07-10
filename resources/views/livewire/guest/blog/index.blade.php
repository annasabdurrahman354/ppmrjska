<div>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content">
        <div class="overflow-hidden">
            <x-guest-navbar></x-guest-navbar>
            <div class="relative">
                <!-- Gradients -->
                <div aria-hidden="true" class="flex -z-[1] absolute -top-48 start-0">
                    <div class="bg-lime-200 opacity-30 blur-3xl w-[1036px] h-[600px] dark:bg-lime-900 dark:opacity-20"></div>
                    <div class="bg-gray-200 opacity-90 blur-3xl w-[577px] h-[300px] transform translate-y-32 dark:bg-neutral-800/60"></div>
                </div>
                <!-- End Gradients -->

                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[340px] h-[340px] border border-dashed border-green-200 rounded-full dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[575px] h-[575px] border border-dashed border-green-200 rounded-full opacity-80 dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[840px] h-[840px] border border-dashed border-green-200 rounded-full opacity-60 dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[1080px] h-[1080px] border border-dashed border-green-200 rounded-full opacity-40 dark:border-green-900/60"></div>
            </div>

            <!-- Title -->
            <div class="mx-auto text-center mt-6">
                <span class="inline-block py-1 px-3 mb-2 text-xs font-semibold text-green-900 bg-white rounded-full">INFORMASI MEDIA MASSA</span>
                <h2 class="text-2xl font-bold font-serif italic md:text-4xl md:leading-tight dark:text-white">Jurnalistik</h2>
            </div>
            <!-- End Title -->

            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto h-full md:h-screen">
                <div class="flex flex-col md:flex-row w-full gap-8">
                    <aside class="md:w-1/3 lg:w-1/4">
                        <div class="bg-white bg-opacity-80 dark:backdrop-blur-md dark:bg-white/10 shadow rounded-xl p-4 sm:p-7">

                            <!-- Section -->
                            <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
                                <h3 class="md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                                    Cari Artikel
                                </h3>
                                <div class="mt-4 space-y-3 relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input wire:model.blur="search" id="search" type="text" class="block w-full p-4 ps-10 py-2 px-3 border-gray-200 shadow-sm text-sm rounded-lg focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Judul artikel...">
                                </div>
                            </div>
                            <!-- End Section -->

                            <!-- Section -->
                            <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
                                <h3 class="md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                                    Kategori Artikel
                                </h3>

                                <div class="mt-2 space-y-3">
                                    <select wire:model.live="kategori_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Pilih kategori artikel...</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{$kategori['id']}}">{{$kategori['nama']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- End Section -->

                            <div class="mt-5 flex justify-end gap-x-2">
                                <button wire:click="resetFilter" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                    Reset
                                </button>
                                <button wire:click="setFilter" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </aside>

                    <main class="flex-1">
                        <div class="space-y-6">
                            @forelse ($blogs as $blog)
                                <div class="flex flex-col items-start p-6 rounded-xl bg-white bg-opacity-80 shadow">
                                    <span class="inline-block py-1 px-2 rounded bg-green-50 text-green-500 text-xs font-medium tracking-widest">{{$blog->kategori->nama}}</span>
                                    <h3 class="sm:text-xl text-lg title-font font-semibold text-gray-800 dark:text-neutral-300  mt-3 mb-3">{{$blog->judul}}</h3>
                                    <p class="mb-4 text-gray-600 dark:text-neutral-400">
                                        {{$blog->deskripsi}}
                                    </p>
                                    <div class="flex items-center flex-wrap pb-3 mb-3 border-b-2 border-gray-100 mt-auto w-full">
                                        <a href="{{route('guest.blog.view', $blog->slug)}}" class="inline-flex items-center gap-x-1 text-green-500 decoration-2 hover:underline font-medium">
                                            Baca Selengkapnya
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                        </a>
                                        <span class="text-gray-400 dark:text-white mr-3 inline-flex items-center ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                        <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                                        </svg>
                                        {{$blog->created_at->isoFormat('dddd, D MMMM Y')}}
                                    </span>
                                        <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                        </svg>
                                        0
                                    </span>
                                    </div>
                                    <a wire:ignore class="inline-flex items-center">
                                        <img alt="blog" src="{{$blog->penulis->getAvatarUrl()}}" class="w-12 h-12 rounded-full flex-shrink-0 object-cover object-center" />
                                        <span class="flex-grow flex flex-col pl-4">
                                        <span class="title-font font-medium text-gray-900">{{$blog->penulis->nama}}</span>
                                        <span class="text-gray-400 text-xs tracking-widest mt-0.5">PENULIS</span>
                                    </span>
                                    </a>
                                </div>
                            @empty
                                <div class="p-4 w-full dark:text-white">
                                    Tidak ada postingan artikel!
                                </div>
                            @endforelse

                            <div class="mt-4">
                                {{ $blogs->links() }}
                            </div>
                        </div>
                    </main>
                </div>
            </div>

        </div>
    </main>

    <x-guest-footer></x-guest-footer>
</div>
