<x-filament-panels::page>
    <div>
        <form wire:submit="cetakJadwal" class="fi-form">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary" class="w-fit mt-6" wire:loading.remove>
                Cetak Jadwal
            </x-filament::button>
            <x-filament::button color="primary" class="w-fit mt-6" disabled wire:loading>
                Loading...
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
