<div class="flex justify-center items-center h-full">
    <div class="w-[40%] h-50 w-max-500 background-secondary-color rounded">
        <p class="mt-6 text-2xl font-inter text-center">Digite o email:</p>

        <form wire:submit.prevent="submit" class="mr-auto px-5 pt-10">
            <input wire:model="email" type="email" class="text-xl bg-white py-1 rounded outline-0 px-1 focus:px-2.5 transition-all duration-100 ease-in-out w-full">
            <fieldset class="flex justify-between my-4">
                <a href="{{route('login')}}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
                Voltar
                </a>

                @error('email')
                    <span 
                        class="text-red-600 text-center m-0 p-0 font-inter self-center"
                        x-data="{show:true}"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                    >
                    {{ $message }}
                    </span>
                @enderror

                @if(session('error_send_email'))
                    <span 
                        class="text-red-600 text-center m-0 p-0 font-inter self-center"
                        x-data="{show:true}"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                    >
                    {{ session('error_send_email') }}
                    </span>
                @endif
                
                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
                    Enviar código de confirmação
                </button>
            </fieldset>

            <div wire:loading class="absolute">
                <livewire:loader />
            </div>
        </form>
    </div>
</div>
