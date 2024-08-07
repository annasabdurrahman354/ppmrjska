<div class="lg:col-span-2 lg:my-4 w-full grid grid-flow-row grid-rows-1 gap-6 h-fit">
    <div class="space-y-4">
        <div class="bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 p-4">
            <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
                <h5 class="mb-2 md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                    Cari Artikel
                </h5>
                <hr class="border sm:mx-auto dark:border-neutral-700" />
                <div class="mt-4 space-y-3 relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input wire:model.blur="search" id="search" type="text" class="block w-full p-4 ps-10 py-2 px-3 border-gray-200 shadow-sm text-sm rounded-lg focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Judul artikel...">
                </div>
                <div wire:ignore class="mt-2 space-y-3">
                    <!-- Select -->
                    <select wire:model.live="kategori_id" data-hs-select='{
                                                  "placeholder": "Pilih kategori...",
                                                  "toggleTag": "<button type=\"button\"></button>",
                                                  "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 px-4 pe-9 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-green-500 focus:ring-green-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
                                                  "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                                  "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                                  "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"flex-shrink-0 size-3.5 text-green-600 dark:text-green-500\" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                  "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"flex-shrink-0 size-3.5 text-gray-500 dark:text-neutral-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                                }' class="hidden">
                        <option selected="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{$kategori['id']}}">{{$kategori['nama']}}</option>
                        @endforeach
                    </select>
                    <!-- End Select -->
                </div>
                <div class="mt-5 flex justify-end gap-x-2">
                    <button wire:click="find" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                        Cari
                    </button>
                </div>
            </div>
            <!-- End Section -->
        </div>
        <div class="bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 p-4">
            <h5 class="mb-2 md:text-lg font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                Artikel Lainnya
            </h5>
            <hr class="border sm:mx-auto dark:border-neutral-700" />
            <div class="w-full grid grid-flow-row grid-cols-1 space-y-2 divide-y-2 pt-4">
                @forelse ($blogs as $blog)
                    <div class="flex flex-col items-start">
                        <span class="inline-block py-1 px-2 rounded bg-green-50 text-green-500 text-xs font-medium tracking-widest">{{$blog->kategori->nama}}</span>
                        <h3 class="sm:text-xl title-font font-semibold text-gray-800 dark:text-neutral-300 mt-2 mb-2">{{$blog->judul}}</h3>
                        <p class="mb-3 text-sm text-gray-600 dark:text-neutral-400">
                            {{$blog->deskripsi}}
                        </p>
                        <div class="flex items-center flex-wrap pb-3 mb-3 border-b-2 border-gray-100 mt-auto w-full">
                            <a href="{{route('guest.blog.view', $blog->slug)}}" class="inline-flex items-center gap-x-1 text-green-500 decoration-2 hover:underline">
                                Baca Selengkapnya
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="w-full dark:text-white">
                        Tidak ada postingan artikel!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
