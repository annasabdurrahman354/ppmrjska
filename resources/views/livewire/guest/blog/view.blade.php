@section('title')
    {{$blog->judul}}
@stop

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

        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto">
            <div class="w-full mx-auto dark:bg-gray-900">
                <section class="px-4 mx-auto w-full">
                    <div class="grid grid-flow-row lg:grid-flow-col lg:grid-cols-6 gap-5">
                       <div class="bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 lg:col-span-4 lg:my-4 w-full">
                            <div class="md:flex justify-between items-center align-middle p-4 space-y-3 md:space-y-0">
                                <p class="dark:text-white">Ditulis oleh <span class="font-semibold"> {{$blog->penulis->nama}} </span></p>
                                <div class="flex gap-2">
                                    <button type="button" id="button-share" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg  class="w-5 h-5 mr-2 -ml-1"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="6" cy="12" r="3" />  <circle cx="18" cy="6" r="3" />  <circle cx="18" cy="18" r="3" />  <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />  <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" /></svg>
                                        Bagikan
                                    </button>
                                    <button type="button" id="button-copy-link" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg class="w-5 h-5 mr-2 -ml-1"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2" />  <rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                                        Salin Link
                                    </button>
                                </div>
                            </div>
                            <hr class="border sm:mx-auto dark:border-neutral-700" />

                            <div class="grid grid-cols-1 gap-3 mx-4 mt-4 mb-2">
                                <h2 class="text-2xl font-bold lg:text-4xl dark:text-white">{{$blog->judul}}</h2>
                                <p class="text-lg dark:text-white">{{$blog->deskripsi}}</p>
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
                        <livewire:guest.components.content-sidebar slug={{$slug}} />
                    </div>
                </section>
            </div>
        </div>
        <x-guest-footer></x-guest-footer>
    </main>
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
