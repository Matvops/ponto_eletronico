<div class="flex flex-col items-center justify-center h-[100vh] gap-12">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-32">
        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
    </svg>

    <form id="form-atualizar" action="{{ route('save_updated_profile') }}" method="POST" class="max-h-200 w-[40%] max-w-500 background-secondary-color px-4 rounded shadow-sm">
        @csrf
        
        <div class="my-24 flex flex-col gap-12 w-[70%] max-w-150">
            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md font-inter ">
                @error('username') 
                    <p>Nome <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Nome
                @enderror
                <input type="text" name="username"
                    value="{{ $username }}"
                    class="mt-2 bg-white py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off"
                    >
            </label>

            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md font-inter">
                @error('email') 
                    <p>Email <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Email
                @enderror
                <input type="email" name="email"
                    value="{{ $email }}"
                    class="mt-2 bg-white py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off">
            </label>
        </div>

        <div class=" mx-auto">
            <label class="flex flex-col text-white text-shadow-md font-inter">
                @error('password') 
                    <p class="text-red-500 text-center font-medium text-xl">Confirme a atualização com sua senha <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    <p class="text-red-500 text-center font-medium text-xl">Confirme a atualização com sua senha</p>
                @enderror
                <input type="password" name="password"
                    class="mt-2 bg-white py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off">
            </label>
        </div>

        <div class="flex justify-between my-8">
            <a href="{{ route('home_admin') }}"
                class="bg-gray-800 text-white text-2xl font-medium p-2 rounded shadow-sm hover:bg-gray-700 duration-150 transition-all ease">
                Voltar
            </a>

            <button 
                    onclick="handleAtualizar()"
                    type="button"
                    class="bg-yellow-400 p-2 font-medium text-2xl rounded shadow-sm cursor-pointer hover:bg-yellow-500 duration-150 transition-all ease">
                Atualizar
            </button>
        </div>
    </form>

    <div id="loader" class="hidden absolute left-[50%]">
        <livewire:loader />
    </div>
</div>

<script>

    function handleAtualizar() {

        document.getElementById('loader').classList.remove('hidden');

        document.getElementById("form-atualizar").submit();
    }

</script>