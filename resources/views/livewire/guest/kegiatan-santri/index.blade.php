@section('title')
    Kegiatan Santri
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
            <span class="inline-block py-1 px-3 mb-2 text-xs font-semibold text-green-900 bg-white rounded-full">PROGRAM PENDIDIKAN</span>
            <h2 class="text-2xl font-bold font-serif italic md:text-4xl md:leading-tight dark:text-white">Kegiatan Santri</h2>
        </div>
        <!-- End Title -->

        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto">

        </div>
        <x-guest-footer></x-guest-footer>
    </main>
</div>
