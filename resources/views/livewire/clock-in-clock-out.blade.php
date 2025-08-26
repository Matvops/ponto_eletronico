<div class="h-[100vh] flex">
    <x-left-menu />

    <div class="w-full h-full">
        <p class="px-2 mt-12 text-2xl font-inter w-fit"><strong>Último registro:</strong> {{ $last_type_clock }} {{$last_clock_time}} </p>

        <div class="flex flex-col justify-center items-center h-full">
            @if($status)
                <p class="text-2xl uppercase text-red-600 font-inter pb-4 font-semibold">{{ $status }}</p>
            @endif
            <form action="{{ route('punch_clock') }}" method="POST">
                @csrf
                <input type="hidden" name="tis_id" value="{{ Crypt::encrypt($id) }}">
                <button
                    @if($id == 0) disabled @endif
                    @if($id == 0) type="button" @endif
                    @if($id == 0)
                        class="bg-blue-400/30 text-white font-semibold px-2 py-4 rounded-sm shadow-sm font-inter text-3xl"
                    @else
                        class="cursor-pointer background-primary-color text-white font-semibold px-2 py-4 rounded-sm shadow-sm font-inter text-3xl hover:px-3 transition-all duration-100 ease"
                    @endif
                    >
                        Bater Ponto de {{ $actual_type_clock }}
                    </button>
            </form>
        </div>
    </div>
</div>
