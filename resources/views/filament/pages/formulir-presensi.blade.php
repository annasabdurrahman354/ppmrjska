<x-filament-panels::page>
    <div>
        <form wire:submit="loadRekapPresensi" class="fi-form">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary" class="w-fit mt-6" wire:loading.remove>
                Rekap Presensi
            </x-filament::button>
            <x-filament::button color="primary" class="w-fit mt-6" disabled wire:loading>
                Loading...
            </x-filament::button>
        </form>
        @if(!empty($distinctTanggalSesi) && !empty($attendanceData) && $merekap)
            <x-filament::section class="mt-6 overflow-auto" wire:loading.remove>
            <x-slot name="heading">
                Form Presensi
            </x-slot>
            <x-slot name="headerEnd">
                <x-filament::button wire:click="exportRekapPresensi" color="secondary" wire:loading.remove>
                    Export Excel
                </x-filament::button>
                <x-filament::button color="secondary" disabled wire:loading>
                    Loading...
                </x-filament::button>
            </x-slot>

            <div>
                <x-form-presensi :attendanceData="$attendanceData" :distinctTanggalSesi="$distinctTanggalSesi" :jenis_kelamin="$jenis_kelamin" :bulan="$bulan" :kelas="$kelas"></x-form-presensi>
            </div>
        </x-filament::section>
        @elseif($merekap && $distinctTanggalSesi == [])
            <div class="mt-6" wire:loading.remove>
            <x-shout::shout type="danger" color="danger">
                Tidak ada data presensi!
            </x-shout::shout>
            </div>
        @endif
    </div>
</x-filament-panels::page>
