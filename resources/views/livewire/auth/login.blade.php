<div class="flex">
    <form action="" method="POST" class="px-6 mt-12 text-shadow-sm flex flex-col w-[30%] justify-between">
        @csrf
        <legend class="text-white font-inter font-semibold tracking-wide text-shadow-lg text-6xl">Login</legend>
        <div class="my-32 flex flex-col gap-12">
            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md font-inter">
                @error('email') 
                    <p>Email <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Email
                @enderror
                <input type="email" name="email"
                    class="mt-2 background-secondary-color py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration- ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off">
            </label>
            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md font-inter">
                @error('password') 
                    <p>Senha <span class="text-red-600 inline text-xl font-normal text-shadow-xs"> {{ $message }}</span></p> 
                @else 
                    Senha
                @enderror
                <input type="password" name="password"
                    class="mt-2 background-secondary-color py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded">
                <a href="{{route('forgot_password')}}" class="font-inter text-blue-700 font-light text-lg underline text-shadow-xs pt-2 hover:text-blue-900 transition-colors duration-150 ease-in-out">Esqueci minha senha</a>
            </label>

            @if(session('error_auth'))
                <span 
                    class="text-red-600 text-center m-0 p-0 font-inter"
                    x-data="{show:true}"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                >
                {{ session('error_auth') }}
                </span>
            @endif
        </div>
        <button
            type="submit"
            class="bg-white hover:bg-gray-100 text-3xl text-gray-800 font-semibold py-3 px-4 border border-gray-400 rounded shadow cursor-pointer font-inter mb-10 mx-auto">
        Entrar
        </button>
    </form>
    <div class="background-primary-color w-[70%] h-screen m-0 p-0 ml-auto text-center h-screen">
        <h1 class="text-white font-bold text-8xl font-inter m-0 mt-32 tracking-wider text-shadow-lg">Ponto Prati
        </h1>
        <img src="{{ Storage::url('images/pngwing.com.png') }}" alt="ponto" class="mx-auto my-32">
    </div>

    <script>
        window.addEventListener('success_store_new_password', (event) => {
            Swal.fire({
                title: event.detail[0],
                text: event.detail[2],
                position: event.detail[3],
                icon: event.detail[1],
                timer: 2000,
                showConfirmButton: false,
            })
        });
    </script>
</div>
