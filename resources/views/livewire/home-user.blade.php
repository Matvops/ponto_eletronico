<div class="flex h-[100vh]">
    <x-left-menu />
    <div class="flex flex-col justify-center items-center gap-48 px-24 w-full">

        <div class="bg-white w-[30%] max-w-300 h-100 rounded-sm shadow-md">
            <div class="my-12">
                @if($statusTimeBalance)
                    <img src="{{ asset('storage/images/increase.png')}}" class="w-[25%] mx-auto">
                @else
                    <img src="{{ asset('storage/images/vecteezy_red-arrow-pointing-down-indicating-a-decrease-or-decline_59253729.png')}}" class="w-[30%] mx-auto">
                @endif
            </div>

            <p class="font-inter text-4xl text-center">Banco de horas</p>
            @if($statusTimeBalance)
                <p class="font-inter text-3xl text-green-600 text-center font-light my-12">{{$timeBalance}}</p>
            @else
                <p class="font-inter text-3xl text-red-600 text-center font-light my-16">{{$timeBalance}}</p>
            @endif
        </div>

    </div>
</div>