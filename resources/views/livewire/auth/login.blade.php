<div>
    <!-- Hero -->
    <div class="flex justify-center items-center lg:h-screen w-full relative bg-gradient-to-bl from-green-100 via-transparent dark:from-green-950 dark:via-transparent">
        <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto py-6">
            <!-- Grid -->
            <div class="grid items-center md:grid-cols-2 gap-8 lg:gap-16">
                <div>
                    <div class="container mt-4 md:mb-12 max-w-2xl w-full mx-auto lg:px-4">
                        <div class="flex flex-wrap justify-between w-full">
                            <div class="">
                                <a href="{{route('guest.index')}}" class="flex items-center align-middle">
                                    <img src="{{ asset('index/Logo PPM.png') }}" class="h-20 mr-4" alt="PPM Roudlotul Jannah Surakarta">
                                    <div class="flex flex-col justify-between h-full">
                                        <h1 class="inline-block font-messiri font-bold text-2xl lg:text-3xl  bg-clip-text bg-gradient-to-l from-green-600 to-emerald-500 text-transparent dark:from-green-400 dark:to-emerald-400">
                                            PPM Roudlotul Jannah Surakarta
                                        </h1>
                                        <div class="font-medium text-gray-600 dark:text-neutral-400">Sistem Manajemen Kelas</div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Blockquote -->
                    <blockquote class="hidden md:block relative max-w-sm">
                        <svg
                            class="absolute top-0 start-0 transform -translate-x-6 -translate-y-8 size-16 text-gray-200 dark:text-neutral-800"
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z"
                                fill="currentColor"
                            />
                        </svg>

                        <div class="relative z-10">
                            <p class="text-xl font-messiri font-medium text-gray-800 dark:text-white">
                                Sarjana yang mubaligh. <span class="text-xl font-medium text-gray-800 dark:text-white italic font-serif">Mubaligh yang sarjana.</span>
                            </p>
                        </div>
                    </blockquote>
                    <!-- End Blockquote -->
                </div>
                <!-- End Col -->

                <div>
                    <!-- Form -->
                    <form wire:submit.prevent="login">
                        <div class="lg:max-w-lg lg:mx-auto lg:me-0 ms-auto">
                            <!-- Card -->
                            <div class="p-4 sm:p-7 flex flex-col bg-white rounded-2xl shadow-lg dark:bg-neutral-900">
                                <div class="text-center">
                                    <h1 class="block text-2xl font-messiri font-bold text-gray-800 dark:text-white ">Selamat Datang</h1>
                                </div>
                                <div class="mb-3 mt-6 flex items-center border-b border-gray-200 dark:border-neutral-700"></div>

                                <!-- Grid -->
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Input Group -->
                                    <div>
                                        <!-- Floating Input -->
                                        <div class="relative">
                                            <input
                                                type="text"
                                                wire:model.lazy="email"
                                                id="hs-hero-signup-form-floating-input-email"
                                                class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                                                placeholder="you@email.com"
                                            />
                                            <label
                                                for="hs-hero-signup-form-floating-input-email"
                                                class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent origin-[0_0] dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:scale-90 peer-focus:translate-x-0.5 peer-focus:-translate-y-1.5 peer-focus:text-gray-500 dark:peer-focus:text-neutral-500 peer-[:not(:placeholder-shown)]:scale-90 peer-[:not(:placeholder-shown)]:translate-x-0.5 peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500 dark:peer-[:not(:placeholder-shown)]:text-neutral-500 dark:text-neutral-500"
                                            >
                                                Email
                                            </label>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <!-- End Floating Input -->
                                    </div>
                                    <!-- End Input Group -->

                                    <!-- Input Group -->
                                    <div class="col-span-full">
                                        <!-- Floating Input -->
                                        <div class="relative">
                                            <input
                                                type="password"
                                                wire:model.lazy="password"
                                                id="hs-hero-signup-form-floating-input-current-password"
                                                class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2"
                                                placeholder="********"
                                            />
                                            <label
                                                for="hs-hero-signup-form-floating-input-current-password"
                                                class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent origin-[0_0] dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:scale-90 peer-focus:translate-x-0.5 peer-focus:-translate-y-1.5 peer-focus:text-gray-500 dark:peer-focus:text-neutral-500 peer-[:not(:placeholder-shown)]:scale-90 peer-[:not(:placeholder-shown)]:translate-x-0.5 peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500 dark:peer-[:not(:placeholder-shown)]:text-neutral-500 dark:text-neutral-500"
                                            >
                                                Kata Sandi
                                            </label>
                                        </div>
                                        <!-- End Floating Input -->
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- End Input Group -->
                                </div>
                                <!-- End Grid -->
                                <div class="my-3 flex items-center border-b border-gray-200 dark:border-neutral-700"></div>

                                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                                    Lupa kata sandi?
                                    <a class="text-blue-600 decoration-2 hover:underline font-medium dark:text-blue-500" href="#">
                                        Perbarui disini!
                                    </a>
                                </p>

                                <div class="mt-5">
                                    <button
                                        wire:loading.remove
                                        type="submit"
                                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                                    >
                                        Masuk
                                    </button>

                                    <button wire:loading disabled type="button" class="w-full py-3 px-4 text-sm font-semibold text-green-700 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-green-700 focus:text-green-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center">
                                        <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-green-200 animate-spin dark:text-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2"/>
                                        </svg>
                                        Loading...
                                    </button>
                                </div>
                            </div>
                            <!-- End Card -->
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End Clients Section -->
    </div>
    <!-- End Hero -->
</div>
