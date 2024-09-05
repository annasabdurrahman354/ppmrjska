<div class="flex flex-col gap-4">
    @foreach($ketercapaianMateri as $semester)
        <!-- File Uploading Progress Form -->
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
            <div class="bg-gray-50 border-b rounded-t-xl py-3 px-4 md:py-4 md:px-5 dark:bg-white/10 dark:border-gray-700">
                <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">
                    Semester {{$semester['semester']}}
                </p>
            </div>
            <!-- Body -->
            <div class="p-4 md:p-5 space-y-4">
                @foreach($semester['materi'] as $materi)
                    <div>
                        <!-- Title Content -->
                        <div class="mb-2 flex justify-between items-center">
                            <div class="flex items-center gap-x-3">
                                <div>
                                    <div class="flex flex-row gap-x-2 items-center">
                                        @if($materi['status_tercapai'])
                                            <div class="relative">
                                                <svg class="shrink-0 size-4 text-primary-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                                                </svg>
                                                <span class="sr-only">Tuntas</span>
                                            </div>
                                        @endif
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                                            {{$materi['nama_materi'].' (Halaman '.$materi['halaman_awal'].'-'.$materi['halaman_akhir'].')'}}
                                        </p>
                                    </div>

                                    @if($materi['materi_type'] != \App\Models\MateriHafalan::class && !$materi['status_tercapai'])
                                        <p class="text-xs text-gray-500 dark:text-white">Kurang {{$materi['total_halaman'] - $materi['jumlah_halaman_tercapai']}} halaman</p>
                                    @endif
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-x-3">
                                @if(can('update_kurikulum'))
                                    @if($materi['status_tercapai'])
                                        {{ ($this->belumTuntaskanMateriAction)(['plotKurikulumMateriId' => $materi['plotKurikulumMateriId']]) }}
                                    @else
                                        {{ ($this->tuntaskanMateriAction)(['plotKurikulumMateriId' => $materi['plotKurikulumMateriId']]) }}
                                    @endif
                                @endif
                            </div>
                        </div>
                        <!-- End Title Content -->
                        <!-- Progress Bar -->
                        @if($materi['materi_type'] != \App\Models\MateriHafalan::class)
                            <div class="flex items-center gap-x-3 whitespace-nowrap">
                                <div class="flex w-full bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="{{$materi['status_tercapai'] ? '100' : $materi['persen_tercapai']}}" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                    <div class="flex flex-col justify-center rounded-full overflow-hidden bg-primary-500 text-xs text-white text-center whitespace-nowrap transition duration-500" style="width: {{$materi['status_tercapai'] ? '100' : $materi['persen_tercapai']}}%;"></div>
                                </div>
                                <div class="w-9 text-end">
                                    <span class="text-sm text-gray-800 dark:text-white">{{$materi['status_tercapai'] ? '100' : $materi['persen_tercapai']}}%</span>
                                </div>
                            </div>
                        @endif

                        <!-- End Progress Bar -->
                    </div>
                @endforeach
            </div>
            <!-- End Body -->
            <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 rounded-b-xl py-2 px-4 md:px-5 dark:bg-white/10 dark:border-gray-700">
                <div class="flex flex-wrap justify-between items-center gap-x-3">
                    <div>
                        @if(count(array_filter($semester['materi'], function($item) { return $item['status_tercapai']; } )) != 0)
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ count(array_filter($semester['materi'], function($item) { return $item['status_tercapai']; } )) }} {{' materi tuntas, '}}
                        </span>
                        @endif
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ count(array_filter($semester['materi'], function($item) { return !$item['status_tercapai']; } )) }} {{' materi belum tuntas'}}
                        </span>
                    </div>
                </div>
            </div>
            <!-- End Footer -->
        </div>
        <!-- End File Uploading Progress Form -->
    @endforeach
</div>
