<div class="flex items-center justify-center h-[100vh]">
    <form action="{{ route('register') }}" method="POST" id="form-register" class="max-h-200 w-[40%] max-w-500 background-secondary-color px-4 rounded shadow-sm pl-8 pr-24 ">
        @csrf
        <div class="flex flex-col gap-12 my-8">
            <label class="flex flex-col text-gray-700 text-3xl font-medium text-shadow-md font-inter ">
                @error('username') 
                    <p>Nome <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Nome
                @enderror
                <input type="text" name="username"
                    class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off"
                    >
            </label>

            <label class="flex flex-col text-gray-700 text-3xl font-medium text-shadow-md font-inter ">
                @error('email') 
                    <p>Email <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Email
                @enderror
                <input type="email" name="email"
                    class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off"
                    >
            </label>

            <label class="flex flex-col text-gray-700 text-3xl font-medium text-shadow-md font-inter ">
                @error('password') 
                    <p>Senha <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Senha
                @enderror
                <input type="password" name="password"
                    class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off"
                    >
            </label>

            <label class="flex flex-col text-gray-700 text-3xl font-medium text-shadow-md font-inter ">
                @error('confirmation_password') 
                    <p>Confirme a senha <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Confirme a senha
                @enderror
                <input type="password" name="confirmation_password"
                    class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off"
                    >
            </label>
        </div>

        <div class="flex">
            <div class="flex flex-col gap-2 mb-4">
                <label class="cursor-pointer font-inter text-lg">
                    <input id="ADMIN" type="checkbox" name="role" value="admin" class="cursor-pointer"> ADMINISTRADOR
                </label>

                <label class="cursor-pointer font-inter text-lg">
                    <input id="USER" type="checkbox" name="role" value="user" class="cursor-pointer"> USUÁRIO
                </label>
            </div>

            <div class="flex flex-col gap-2 ml-2">
                @error('role') 
                    <p><span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @enderror
                 @error('role') 
                    <p><span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @enderror
            </div>
        </div>

        <div class="flex justify-between my-8">
            <a href="{{ route('home_admin') }}"
                class="bg-gray-800 text-white text-2xl font-medium p-2 rounded shadow-sm hover:bg-gray-700 duration-150 transition-all ease">
                Voltar
            </a>

            <button 
                    onclick="handleCadastrar()"
                    type="button"
                    class="text-white background-primary-color p-2 font-medium text-2xl rounded shadow-sm cursor-pointer hover:bg-blue-200 duration-150 transition-all ease">
                Cadastrar 
            </button>
        </div>
    </form>

    <div id="loader" class="hidden">
        <livewire:loader />
    </div>
</div>

<script>

    const buttonAdmin = document.getElementById('ADMIN');

    buttonAdmin.addEventListener('click', (event) => {

        if(buttonUser.checked) 
            buttonUser.checked = false;
        
        buttonAdmin.target.checked = !buttonAdmin.target.checked;
    });

    const buttonUser = document.getElementById('USER');

    buttonUser.addEventListener('click', (event) => {

        if(buttonAdmin.checked) 
            buttonAdmin.checked = false;
        
        buttonUser.target.checked = !buttonUser.checked;
    });

    function handleCadastrar() {
        document.getElementById('loader').classList.remove('hidden');
        document.getElementById('form-register').submit();
    }
</script>

