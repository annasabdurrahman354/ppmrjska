<div>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content">
        <div>
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
            <div class="mx-auto text-center">
                <span class="inline-block py-1 px-3 mb-2 text-xs font-semibold text-green-900 bg-white rounded-full">INFORMASI MEDIA MASSA</span>
                <h2 class="text-2xl font-bold font-serif italic md:text-4xl md:leading-tight dark:text-white">Jurnalistik</h2>
            </div>
            <!-- End Title -->

            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto">
                <div class="w-full mx-auto dark:bg-gray-900">
                    <section class="text-gray-600 body-font px-4 mx-auto w-full">
                        <div class="grid grid-flow-row lg:grid-flow-col lg:grid-cols-6 gap-5">
                            <div class="border bg-white border-gray-300 rounded-md shadow-sm lg:col-span-4 lg:my-4 w-full">
                                <div class="md:flex justify-between items-center align-middle p-4 space-y-3 md:space-y-0">
                                    <p>Ditulis oleh <span class="font-semibold"> {{$blog->penulis->nama}} </span></p>
                                    <div class="flex gap-2">
                                        <button type="button" id="button-share" class="text-center inline-flex items-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg px-3 py-2 text-xs font-medium dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                                            <svg  class="w-5 h-5 mr-2 -ml-1"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="6" cy="12" r="3" />  <circle cx="18" cy="6" r="3" />  <circle cx="18" cy="18" r="3" />  <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />  <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" /></svg>
                                            Bagikan
                                        </button>
                                        <button type="button" id="button-copy-link" class="hidden md:inline-flex text-center items-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg px-3 py-2 text-xs font-medium dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                                            <svg class="w-5 h-5 mr-2 -ml-1"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2" />  <rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                                            Salin Link
                                        </button>
                                    </div>
                                </div>
                                <hr class="border-gray-300 sm:mx-auto dark:border-gray-700" />

                                <div class="grid grid-cols-1 gap-3 mx-4 mt-4 mb-2">
                                    <h2 class="text-2xl font-bold lg:text-3xl text-gray-800 dark:text-white">{{$blog->judul}}</h2>
                                    <p>{{$blog->deskripsi}}</p>
                                    <div class="flex items-center gap-x-5">
                                        <a class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-md text-sm font-medium border border-gray-200 bg-white text-gray-800 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:bg-blue-800/30 hover:bg-blue-50 dark:hover:bg-neutral-800" href="{{route('guest.blog.index', ['kategori_id' => $blog->kategori->id])}}">
                                            <p>{{$blog->kategori->nama}}</p>
                                        </a>
                                        <p class="text-xs sm:text-sm text-gray-800 dark:text-neutral-200">{{$blog->created_at->isoFormat('dddd, D MMMM Y')}}</p>
                                    </div>
                                </div>

                                <div class="w-full p-4">
                                    <img class="w-full object-cover rounded-xl" src="{{$blog->getFirstMediaUrl('blog_cover')}}" alt="{{$blog->judul}}">
                                </div>
                                <img class="w-full" src="" alt=""/>
                                <div class="w-full h-fit px-4 ck-content font-sans">
                                    {!! $blog->konten !!}
                                </div>
                            </div>
                            <div class="lg:col-span-2 lg:my-4 w-full grid grid-flow-row grid-rows-1 gap-6 h-fit">
                                <div class="space-y-4">
                                    <div class="border bg-white border-gray-300 rounded-md shadow-sm p-4">
                                        <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
                                            <h3 class="mb-2 md:text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                                Cari Artikel
                                            </h3>
                                            <hr class="border-gray-300 sm:mx-auto dark:border-gray-700" />
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
                                    <div class="border bg-white border-gray-300 rounded-md shadow-sm p-4">
                                        <h3 class="mb-2 md:text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                            Artikel Lainnya
                                        </h3>
                                        <hr class="border-gray-300 sm:mx-auto dark:border-gray-700" />
                                        <div class="w-full grid grid-flow-row grid-cols-1 space-y-2 divide-y-2 pt-6">
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
                                                Belum ada postingan blog!
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <x-guest-footer></x-guest-footer>
</div>

@push('scripts')
    <script>
        document.getElementById('button-share').addEventListener('click', event => {
            if (navigator.share) {
                navigator.share({
                    title: '{!!$blog->judul!!}',
                    url: window.location.href,
                }).then(() => {
                    console.log('Thanks for sharing! {!!$blog->judul!!} in '+ window.location.href);
                }).catch(console.error);
            }
        });

        document.getElementById('button-copy-link').addEventListener('click', event => {
            navigator.clipboard.writeText(window.location.href);
        });
    </script>
@endpush

@push('styles')
    @once
        <link rel="stylesheet" href="{{asset('vendor/ckeditor/ckeditor.css')}}"/>
    @endonce
@endpush
