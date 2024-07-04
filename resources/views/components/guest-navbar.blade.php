<!-- Header -->
<header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full text-sm py-3 md:py-0">
    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8" aria-label="Global">
        <div class="relative lg:flex lg:items-center lg:justify-between">
            <div class="flex items-center justify-between">
                <a href="{{route('guest.index')}}" class="flex items-center space-x-2 rtl:space-x-reverse">
                    <img class="w-16 h-auto py-4" src="{{asset('index/Logo PPM.png')}}" alt="Logo PPM Roudlotul Jannah Surakarta">
                    <span class="self-center text-xl font-messiri font-bold text-green-700 whitespace-nowrap dark:text-white"> PPM RJ Surakarta</span>
                </a>
                <div class="lg:hidden">
                    <button type="button" class="hs-collapse-toggle size-9 flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-neutral-800 dark:hover:bg-neutral-700" data-hs-collapse="#navbar-collapse-basic-content" aria-controls="navbar-collapse-basic-content" aria-label="Toggle navigation">
                        <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6"/>
                            <line x1="3" x2="21" y1="12" y2="12"/>
                            <line x1="3" x2="21" y1="18" y2="18"/>
                        </svg>
                        <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="navbar-collapse-basic-content" class="hidden overflow-hidden transition-all duration-300 basis-full grow lg:block">
                <div data-hs-scrollspy="#scrollspy" class="[--scrollspy-offset:220] md:[--scrollspy-offset:70] flex flex-col gap-x-0 mt-5 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-end md:gap-x-7 md:mt-0 md:ps-7 md:divide-y-0 md:divide-solid dark:divide-neutral-700">
                    <p class="font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500 my-3 md:my-6 group relative w-max">
                        <a href="{{route('guest.blog.index')}}" aria-current="page">Blog</a>
                        <span class="absolute -bottom-1 left-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                        <span class="absolute -bottom-1 right-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                    </p>

                    <p class="font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500 mb-3 md:my-6 group relative w-max">
                        <a href="{{route('guest.media.index')}}" aria-current="page">Media</a>
                        <span class="absolute -bottom-1 left-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                        <span class="absolute -bottom-1 right-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                    </p>

                    <div data-hs-scrollspy-group class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--trigger:hover] w-fit mb-3 md:my-6">
                        <button type="button"
                            id="hs-dropdown-default"
                            class="flex items-center w-full font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500" >
                            Tentang Kami
                            <svg class="flex-shrink-0 ms-1 size-4 dark:text-neutral-500"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div class="hs-dropdown-menu hidden z-10 bg-white transition-opacity duration-300 opacity-0 hs-dropdown-open:transition-none hs-dropdown-open:duration-0 sm:hs-dropdown-open:transition-[opacity,margin] sm:hs-dropdown-open:duration-[150ms] sm:transition-[opacity,margin] sm:duration-[150ms] hs-dropdown-open:opacity-100 sm:w-48 sm:shadow-md rounded-lg py-2 sm:px-2 dark:bg-neutral-800 sm:dark:border dark:border-neutral-700 dark:divide-neutral-700 before:absolute top-full sm:border before:-top-5 before:start-0 before:w-full before:h-5">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Profil
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Sejarah
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Visi Misi
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Fasilitas
                            </a>
                        </div>
                    </div>

                    <div data-hs-scrollspy-group class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--trigger:hover] w-fit mb-3 md:my-6">
                        <button type="button"
                            id="hs-dropdown-default"
                            class="flex  items-center w-full font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500">
                            Program Pendidikan
                            <svg class="flex-shrink-0 ms-1 size-4 dark:text-neutral-500"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div class="hs-dropdown-menu hidden z-10 bg-white transition-opacity duration-300 opacity-0 hs-dropdown-open:transition-none hs-dropdown-open:duration-0 sm:hs-dropdown-open:transition-[opacity,margin] sm:hs-dropdown-open:duration-[150ms] sm:transition-[opacity,margin] sm:duration-[150ms] hs-dropdown-open:opacity-100 sm:w-48 sm:shadow-md rounded-lg py-2 sm:px-2 dark:bg-neutral-800 sm:dark:border dark:border-neutral-700 dark:divide-neutral-700 before:absolute top-full sm:border before:-top-5 before:start-0 before:w-full before:h-5">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Kurikulum
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Agenda Santri
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Ekstrakulikuler
                            </a>
                        </div>
                    </div>

                    <div data-hs-scrollspy-group class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--trigger:hover] w-fit mb-3 md:my-6">
                        <button
                            type="button"
                            id="hs-dropdown-default"
                            class="flex  items-center w-full font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500"
                        >
                            Peta
                            <svg
                                class="flex-shrink-0 ms-1 size-4 dark:text-neutral-500"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div class="hs-dropdown-menu hidden z-10 bg-white transition-opacity duration-300 opacity-0 hs-dropdown-open:transition-none hs-dropdown-open:duration-0 sm:hs-dropdown-open:transition-[opacity,margin] sm:hs-dropdown-open:duration-[150ms] sm:transition-[opacity,margin] sm:duration-[150ms] hs-dropdown-open:opacity-100 sm:w-48 sm:shadow-md rounded-lg py-2 sm:px-2 dark:bg-neutral-800 sm:dark:border dark:border-neutral-700 dark:divide-neutral-700 before:absolute top-full sm:border before:-top-5 before:start-0 before:w-full before:h-5">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Denah Lokasi Pondok
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                Perguruan Tinggi Terdekat
                            </a>
                        </div>
                    </div>

                    <p class="font-medium text-gray-600 hover:text-gray-500 dark:text-neutral-400 dark:hover:text-neutral-500 mb-3 md:my-6 group relative w-max">
                        <a href="{{route('guest.kontak.index')}}" aria-current="page">Kontak Kami</a>
                        <span class="absolute -bottom-1 left-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                        <span class="absolute -bottom-1 right-1/2 w-0 transition-all h-0.5 bg-lime-600 group-hover:w-3/6"></span>
                    </p>

                    <div class="pt-3 md:pt-0">
                        <a class="relative group inline-block py-2.5 px-3 gap-x-2 text-sm font-medium text-emerald-900 hover:text-white border border-gray-300 rounded-md overflow-hidden transition duration-300" href="{{route('login')}}">
                            <div class="absolute top-0 right-full w-full h-full bg-green-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500"></div>
                            <span class="relative" data-config-id="auto-txt-15-1">SIMAK Erjes</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- End Header -->
