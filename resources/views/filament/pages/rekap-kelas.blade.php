<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item
            :active="$activeTab === 'persen'"
            wire:click="$set('activeTab', 'persen')"
        >
            Persen
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="$activeTab === 'jumlah'"
            wire:click="$set('activeTab', 'jumlah')"
        >
            Jumlah
        </x-filament::tabs.item>
    </x-filament::tabs>
    {{ $this->table }}


</x-filament-panels::page>
