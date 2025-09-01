<div class="h-[100vh] w-full flex justify-center items-center">
    <main class="px-4 py-6 bg-gray-200 w-[30%] max-w-400 max-h-300 rounded-sm shadow-sm">
        <h1 class="text-center font-semibold text-3xl font-inter mb-2">Atualizar</h1>

        <form action="{{ route('update_profile_by_admin') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$usr_id}}">
            <div class="flex flex-col w-9/10">
                <label class="flex flex-col gap-2 mb-4 text-xl font-inter">
                    @error('username') 
                        <p>Nome <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                    @else 
                        Nome
                    @enderror
                    <input name="username" type="text" class="border-0 outline-none bg-gray-100 rounded-sm py-1 px-2 text-gray-700 text-lg focus:px-2.5 focus:shadow-xs focus:text-gray-900 transition-all duration-150 ease" value="{{ $username }}">
                </label>

                <label class="flex flex-col gap-2 mb-4 text-xl font-inter">
                    @error('email') 
                        <p>Email <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                    @else 
                        Email
                    @enderror
                    <input name="email" type="email" class="border-0 outline-none bg-gray-100 rounded-sm py-1 px-2 text-gray-700 text-lg focus:px-2.5 focus:shadow-xs focus:text-gray-900 transition-all duration-150 ease" value="{{ $email }}">
                </label>
            </div>

            <h2 class="text-2xl my-4 font-inter">Saldo de horas</h2>
            <div class="flex items-center gap-8">
                @if($statusTimeBalance)
                    <p class="font-inter text-xl text-green-600">{{$timeBalance}}</p>
                @else
                    <p class="font-inter text-xl text-red-600">{{$timeBalance}}</p>
                @endif

                <label class="cursor-pointer text-lg">
                    <input id="redf" wire:click="alterStateCheckBox" type="checkbox" class="bg-gray-200 cursor-pointer border-2 mr-1" @if($checked != 0) checked @endif> 
                    <input type="hidden" name="reset_time_balance" value="{{$checked ? $checked : 'false' }}">
                        Redefinir 
                </label>
            </div>
            @error('reset_time_balance')
                <span class="text-red-600 inline text-lg font-normal text-shadow-xs"> {{ $message }}</span>
            @enderror

            <div class="mt-12 flex justify-between items-center">
                <a href="{{ route('view_users') }}" class="font-inter bg-gray-700 text-white px-8 py-2 text-xl rounded-sm shadow-sm hover:bg-gray-800 transition-all duration-150 ease-in-out">Voltar</a>
                <button type="submit" class="font-inter bg-yellow-300/80  px-8 py-2 text-xl rounded-sm shadow-sm hover:bg-yellow-400/70 transition-all duration-150 ease-in-out cursor-pointer">Atualizar</button>
            </div>
        </form>
    </main>
</div>


<script>
    const button = document.getElementById('redf');

    button.addEventListener('click', () => eventClick());


    function eventClick() {
        console.log('oooi');
    }
</script>