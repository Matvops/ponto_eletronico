<div class="h-[100vh] flex">
    <x-left-menu />

    <div class="w-full h-full">
        <p class="px-2 mt-12 text-2xl font-inter w-fit"><strong>Último registro:</strong> {{ $last_type_clock }} {{$last_clock_time}} </p>

        <div class="flex flex-col justify-center items-center h-full">
            @if($status)
                <p class="text-2xl uppercase text-red-600 font-inter pb-4 font-semibold">{{ $status }}</p>
            @endif
            <button class="background-primary-color text-white font-semibold px-2 py-4 rounded-sm shadow-sm font-inter text-3xl">Bater Ponto de {{ $actual_type_clock }}</button>
        </div>
    </div>
</div>
