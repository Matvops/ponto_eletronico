<div class="flex">
    <form action="" method="POST" class="px-6 mt-12 text-shadow-sm flex flex-col w-[30%]">
        @csrf
        <legend class="text-white font-inter font-semibold tracking-wide text-shadow-lg text-6xl flex-1">Login
        </legend>
        <div class="my-32 flex flex-col gap-12">
            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md">
                Email
                <input type="email" name="email"
                    class="mt-2 background-secondary-color py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration- ease-in-out text-black text-xl font-light rounded"
                    autocomplete="off">
            </label>
            <label class="flex flex-col text-white text-4xl font-medium text-shadow-md">
                Senha
                <input type="password" name="password"
                    class="mt-2 background-secondary-color py-3 px-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded">
            </label>
        </div>
        <livewire:button text="Entrar" />
    </form>
    <div class="background-primary-color w-[70%] h-screen m-0 p-0 ml-auto text-center h-screen">
        <h1 class="text-white font-bold text-8xl font-inter m-0 mt-32 tracking-wider text-shadow-lg">Ponto Prati
        </h1>
        <img src="{{ Storage::url('images/pngwing.com.png') }}" alt="ponto" class="mx-auto my-32">
    </div>
</div>
