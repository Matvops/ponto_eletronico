<div class="h-[100vh] flex">
    <x-left-menu />

    <main class="mx-auto w-[60%] font-inter gap-12 my-24">
        <header class="flex justify-between px-4 py-4 background-secondary-color mb-12 rounded-sm shadow-sm">
            <div>
                <select class="text-white cursor-pointer background-primary-color hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-50 font-medium rounded-lg text-sm px-2 py-2.5 text-center inline-flex items-center dark:bg-blue-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <option>Filtro</option>
                    <option class="background-primary-color cursor-pointer" wire:click="asc">Mais antigos</option>
                    <option class="background-primary-color cursor-pointer" wire:click="desc">Mais recentes</option>
                    <option class="background-primary-color cursor-pointer" wire:click="positivo">Horas positivas</option>
                    <option class="background-primary-color cursor-pointer" wire:click="negativo">Horas negativas</option>
                    <option class="background-primary-color cursor-pointer" wire:click="maisPositivo">Maior saldo positivo</option>
                    <option class="background-primary-color cursor-pointer" wire:click="maisNegativo">Maior saldo negativo</option>
                </select>
            </div>

            <div class="flex border-b-1 pb-1">
                <input wire:model.live="query" type="text" class="outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </header>

        <div class="background-secondary-color rounded-sm shadow-sm overflow-y-scroll max-h-700">
            <section class="p-0 m-0">
                <ul class="p-0 m-0">
                    @foreach ($time_sheets as $time_sheet)
                        <li class="flex items-center justify-between py-4 px-4 border-b-1 border-gray-500 flex justify-around">
                            
                            {{$time_sheet->time_sheet_date}}

                            <p class="font-inter"><strong>Entrada: </strong> {{ $time_sheet->entry ?? '00:00:00' }}</p>
                            <p class="font-inter"><strong>Saida: </strong> {{ $time_sheet->output ?? '00:00:00' }}</p>

                            @if($time_sheet->status === 'NEGATIVO')
                                <p class="text-red-600">{{ $time_sheet->difference }}</p>
                            @else
                                <p class="text-green-600">{{ $time_sheet->difference }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </main>
</div>
