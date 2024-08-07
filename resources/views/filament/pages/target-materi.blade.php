<x-filament-panels::page>
    <div>
        <form wire:submit="loadTargetMateri" class="fi-form">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary" class="w-fit mt-6" wire:loading.remove>
                Muat Kurikulum
            </x-filament::button>
            <x-filament::button color="primary" class="w-fit mt-6" disabled wire:loading>
                Loading...
            </x-filament::button>
        </form>
        @if(!empty($targetMateri) && $merekap)
            <x-filament::section class="mt-6 overflow-auto" wire:loading.remove>
            <x-slot name="heading">
                Progress Ketercapaian Materi
            </x-slot>
            <x-slot name="headerEnd">
                <x-filament::button wire:click="exportTargetMateri" color="secondary" wire:loading.remove>
                    Export PDF
                </x-filament::button>
                <x-filament::button color="secondary" disabled wire:loading>
                    Exporting...
                </x-filament::button>
            </x-slot>

            <div>
                <x-progress-target-materi :targetMateri="$targetMateri"></x-progress-target-materi>
            </div>
        </x-filament::section>
        @elseif($merekap && $targetMateri == [])
            <div class="mt-6" wire:loading.remove>
            <x-shout::shout type="danger" color="danger">
                Belum ada data kurikulum!
            </x-shout::shout>
            </div>
        @endif
    </div>
</x-filament-panels::page>

