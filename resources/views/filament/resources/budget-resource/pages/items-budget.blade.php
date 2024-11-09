<x-filament-panels::page>

    {{ $this->headertInfolist }}


    <div class="mt-6">
        <livewire:list-items-budget :record="$budget"/>
    </div>

    {{ $this->productInfolist }}


</x-filament-panels::page>
