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
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12  mt-6 mx-auto h-full md:h-screen flex items-center">
                <div class="container my-auto mx-auto flex sm:flex-nowrap flex-wrap ">
                    <div class="lg:w-2/3 w-full bg-white shadow rounded-lg overflow-hidden mb-4 sm:mb-0 sm:mr-10 flex items-end justify-start relative">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.19137077174327!2d110.84967552718656!3d-7.5682272597240186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a17d65fe27789%3A0xc1cdfb4c821f3901!2sRoudhotul%20Jannah%20Islamic%20Boarding%20School%20Surakarta%20(LDII)!5e0!3m2!1sen!2sid!4v1720080344403!5m2!1sen!2sid" width="100%" height="100%" class="absolute inset-0" frameborder="0" title="map" marginheight="0" marginwidth="0" scrolling="no" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="bg-white relative flex flex-col lg:flex-row rounded shadow-md mt-64 md:mt-0 w-full p-6 gap-6">
                            <div class="w-fit shrink">
                                <h3 class="mb-3 font-messiri font-semibold text-black dark:text-white">
                                    Alamat
                                </h3>

                                <!-- Grid -->
                                <div class="grid gap-4 sm:gap-6 md:gap-8 lg:gap-12">
                                    <div class="flex gap-4">
                                        <svg class="flex-shrink-0 size-5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>

                                        <div class="grow">
                                            <address class="mt-1 text-sm text-gray-600 not-italic dark:text-white">
                                                Jl. Porong No.17, Pucangsawit, Jebres<br>
                                                Kota Surakarta, Jawa Tengah
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Grid -->
                            </div>
                            <div class="w-fit shrink">
                                <h3 class="mb-3 font-messiri font-semibold text-black dark:text-white">
                                    Email
                                </h3>

                                <!-- Grid -->
                                <div class="grid gap-4 sm:gap-6 md:gap-8 lg:gap-12">
                                    <div class="flex gap-4">
                                        <svg class="flex-shrink-0 size-5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"></path><path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"></path></svg>

                                        <div class="grow">
                                            <div class="mt-1 text-sm text-gray-600 not-italic dark:text-white">
                                                {{ $pengaturan_umum->site_email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Grid -->
                            </div>
                            <div class="w-fit shrink">
                                <h3 class="mb-3 font-messiri font-semibold text-black dark:text-white">
                                    Narahubung
                                </h3>
                                <!-- Grid -->
                                <div class="flex gap-4">
                                        <svg class="flex-shrink-0 size-5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>

                                        <div class="grow">
                                            <ul class="marker:text-blue-600 list-disc ps-5 space-y-2 text-sm text-gray-600 dark:text-white">
                                                @foreach($pengaturan_umum->site_narahubung as $narahubung)
                                                    <li>
                                                        <a class="relative before:absolute before:bottom-0.5 before:start-0 before:-z-[1] before:w-full before:h-1 before:bg-lime-400 hover:before:bg-black focus:outline-none focus:before:bg-black dark:text-white dark:hover:before:bg-white dark:focus:before:bg-white" href="tel:{{$narahubung['nomor_telepon']}}">
                                                            <span>
                                                                {{ $narahubung['nama']}}
                                                            </span>
                                                            <span>
                                                                {{ $narahubung['nomor_telepon']}}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                <!-- End Grid -->
                            </div>
                            <div class="w-fit shrink">
                                <h3 class="mb-3 font-messiri font-semibold text-black dark:text-white">
                                    Penerima Tamu
                                </h3>

                                <div class="flex gap-4">
                                    <svg class="flex-shrink-0 size-5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>

                                    <div class="grow">
                                        <ul class="marker:text-blue-600 list-disc ps-5 space-y-2 text-sm text-gray-600 dark:text-white">
                                            @foreach($pengaturan_umum->site_penerima_tamu as $penerima_tamu)
                                                <li>
                                                    <a class="relative before:absolute before:bottom-0.5 before:start-0 before:-z-[1] before:w-full before:h-1 before:bg-lime-400 hover:before:bg-black focus:outline-none focus:before:bg-black dark:text-white dark:hover:before:bg-white dark:focus:before:bg-white" href="tel:{{$narahubung['nomor_telepon']}}">
                                                            <span>
                                                                {{ $penerima_tamu['nama']}}
                                                            </span>
                                                        <span>
                                                                {{ $penerima_tamu['nomor_telepon']}}
                                                            </span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/3 w-full bg-white shadow rounded-lg md:flex md:flex-col md:ml-auto px-4 py-4 md:mt-0">
                        <div>
                            <h2 class="mb-3 text-xl font-messiri font-semibold text-gray-800 dark:text-neutral-200">
                                Kotak Saran
                            </h2>
                            <div class="relative mb-4">
                                <label for="pengirim" class="block text-sm font-medium mb-2 dark:text-white">Nama Anda</label>
                                <input wire:model.blur='pengirim' type="text" id="pengirim" name="pengirim" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Nama...">
                                <div class="validation-message">
                                    {{ $errors->first('pengirim') }}
                                </div>
                            </div>
                            <div class="relative mb-4">
                                <label for="nomor_telepon" class="block text-sm font-medium mb-2 dark:text-white">Nomor Telepon</label>
                                <div class="max-w-sm space-y-3">
                                    <input wire:model.blur='nomor_telepon' pattern="[0-9]+" id="nomor_telepon" name="nomor_telepon" type="tel" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Nomor telepon...">
                                </div>
                                <div class="validation-message">
                                    {{ $errors->first('nomor_telepon') }}
                                </div>
                            </div>
                            <div class="relative mb-4">
                                <label for="isi" class="block text-sm font-medium mb-2 dark:text-white">Kritik/Saran</label>
                                <textarea wire:model.blur='isi' id="isi" name="isi" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="3" placeholder="Isi saran/kritik..."></textarea>
                                <div class="validation-message">
                                    {{ $errors->first('isi') }}
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Kosongkan nama dan telepon jika menginginkan anonim.</p>
                            <div class="mt-3" wire:loading.remove>
                                <button wire:click="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                                    Kirim
                                </button>
                            </div>
                            <div class="mt-3" wire:loading>
                                <div class="flex items-center justify-center w-fit h-fit rounded-lg">
                                    <div role="status">
                                        <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                                        <span class="sr-only">Memuat...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-guest-footer></x-guest-footer>
</div>
