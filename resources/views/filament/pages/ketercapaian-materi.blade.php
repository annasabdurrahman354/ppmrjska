<x-filament-panels::page>
    <div>
        @if(!empty($ketercapaianMateri))
            <x-filament::section class="mt-6 overflow-auto" wire:loading.remove>
            <x-slot name="heading">
                Progress Ketercapaian Materi
            </x-slot>
            <x-slot name="headerEnd">
                <x-filament::button wire:click="exportKetercapaianMateri" color="secondary" wire:loading.remove>
                    Export PDF
                </x-filament::button>
                <x-filament::button color="secondary" disabled wire:loading>
                    Exporting...
                </x-filament::button>
            </x-slot>

            <div>
                <x-form-ketercapaian-materi :ketercapaianMateri="$ketercapaianMateri"></x-form-ketercapaian-materi>
            </div>
        </x-filament::section>
        @elseif($ketercapaianMateri == [])
            <div class="mt-6" wire:loading.remove>
            <x-shout::shout type="danger" color="danger">
                Belum ada data kurikulum untuk angkatan Anda!
            </x-shout::shout>
            </div>
        @endif
    </div>
</x-filament-panels::page>

