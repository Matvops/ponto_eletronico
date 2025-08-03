<div class="class w-[20%] max-w-100 h-full background-primary-color flex flex-col justify-between py-4 ">
    <a href="{{ route('home_admin')}}" class="text-white font-bold text-3xl font-inter m-0 tracking-wider text-shadow-lg pl-4 pr-16">PONTO PRATI</a>

    <div class="flex flex-col gap-4 py-2 pl-4 pr-16">
        @can('viewAll', App\Models\User::class)
        <a href="#" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">Visualizar usuários</a>
        @endcan
        @can('create', App\Models\User::class)
            <a href="#" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">Cadastro</a>
        @endcan
    </div>

    <a href="#" class="text-center mx-auto">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-16 hover:fill-black transition-all duration-100 ease-in-out">
            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
        </svg>
    </a>
</div>