@section('title', 'Blog')

<div>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" class="overflow-hidden">
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
                    <div class="bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 p-4 sm:p-7">

                        <!-- Section -->
                        <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
                            <h5 class="md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                                Cari Artikel
                            </h5>
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
                            <h5 class="md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                                Kategori Artikel
                            </h5>

                            <div class="mt-2 space-y-3">
                                <select wire:model.live="kategori_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
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
                            <!-- Card -->
                            <div class="group bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                                <a class="sm:flex" href="{{route('guest.blog.view', $blog->slug)}}">
                                    <div class="hidden sm:flex flex-shrink-0 ms-3 mt-3 mb-3 relative rounded-xl overflow-hidden sm:w-[250px] md:w-[240px] w-full">
                                        <img class="size-full absolute top-0 start-0 object-cover" src="{{$blog->getFirstMediaUrl('blog_cover')}}" alt="{{$blog->judul}}">
                                    </div>
                                    <div class="grow">
                                        <div class="p-4 flex flex-col h-full">
                                            <div class="mb-3">
                                                <p class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-md text-xs font-semibold bg-green-100 text-gray-600 dark:bg-neutral-800 dark:text-neutral-200">
                                                    {{$blog->kategori->nama}}
                                                </p>
                                            </div>
                                            <h3 class="text-lg sm:text-2xl font-messiri font-semibold text-gray-800 group-hover:text-green-600 dark:text-neutral-300 dark:group-hover:text-white">
                                                {{$blog->judul}}
                                            </h3>
                                            <p class="mt-1 text-sm md:text-base text-gray-700 dark:text-neutral-400">
                                                {{$blog->deskripsi}}
                                            </p>

                                            <div class="mt-6">
                                                <!-- Avatar -->
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <img class="size-[30px] rounded-full" src="{{$blog->penulis->getAvatarUrl()}}" alt="Penulis artikel {{$blog->judul}}.">
                                                    </div>
                                                    <div class="ms-2.5 sm:ms-3">
                                                        <h4 class="font-medium text-xs text-neutral-600 dark:text-neutral-200">
                                                            {{$blog->penulis->nama}}
                                                        </h4>
                                                        <p class="text-xs text-neutral-500 dark:text-neutral-500">
                                                            {{$blog->created_at->isoFormat('dddd, D MMMM Y')}}
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- End Avatar -->
                                            </div>
                                        </div>
                                    </div>
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
        <x-guest-footer></x-guest-footer>
    </main>
</div>
