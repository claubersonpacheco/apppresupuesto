<div>
    <div class="">
        <!-- Div secundária ocupando 25% -->
        <div >
            {{ $this->headertInfolist }}
        </div>
{{--        <div class="md:w-1/4 ">--}}
{{--            {{ $this->statusInfolist }}--}}
{{--        </div>--}}
    </div>


    <fieldset class="dark:border-gray-900 dark:bg-gray-900 p-4 rounded-lg mt-6 border bg-white">
        <legend class="text-lg font-semibold text-gray-800 dark:text-white">Tipo de presupuesto:</legend>

        <div class="flex gap-x-4"> <!-- Ajuste de gap-x para o espaçamento entre os itens -->
            <label class="flex items-center gap-x-2">
                <x-filament::input.checkbox wire:model="tax" wire:change="updateVisibleColumns" :checked="$tax"/>
                <span>Mostrar % IVA</span>
            </label>

            <label class="flex items-center gap-x-2">
                <x-filament::input.checkbox wire:model="total_tax" wire:change="updateVisibleColumns"
                                            :checked="$total_tax"/>
                <span>Com IVA</span>
            </label>

            <label class="flex items-center gap-x-2">
                <x-filament::input.checkbox wire:model="total" wire:change="updateVisibleColumns" :checked="$total"/>
                <span>Sem IVA</span>
            </label>
        </div>
    </fieldset>


    <div class="mt-6">
        {{ $this->table }}
    </div>

    <div class="mt-6">
        {{ $this->productInfolist }}
    </div>
</div>
