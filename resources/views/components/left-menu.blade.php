<div class="min-w-50 w-fit max-w-100 h-full background-primary-color flex flex-col justify-between py-4 px-4">
    <a href="{{ route('home_admin')}}" class="text-white font-bold text-3xl font-inter m-0 tracking-wider text-shadow-lg w-fit mx-auto">PONTO PRATI</a>

    <div class="flex flex-col gap-4 py-2 w-fit mx-auto">

        @can('viewAny', App\Models\TimeSheet::class)
            <a href="#" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">VISUALIZAR PONTOS</a>
        @endcan

        @can('viewAll', App\Models\User::class)
        <a href="{{ route('view_users') }}" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">VISUALIZAR USUÁRIOS</a>
        @endcan

        @can('create', App\Models\User::class)
            <a href="{{ route('register_user') }}" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">CADASTRAR</a>
        @endcan

        @can('viewAll', App\Models\TimeSheet::class)
            <a href=" {{route('view_days')}} " class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">VISUALIZAR DIAS</a>
        @endcan

         @can('viewAll', App\Models\TimeSheet::class)
            <a href="#" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">HISTÓRICO</a>
        @endcan
        
        @can('view', App\Models\TimeSheet::class)
            <a href="#" class="text-white text-xl font-medium font-inter hover:text-gray-800 transition-all duration-100 ease">BATER PONTO</a>
        @endcan
    </div>

    <a href="{{ route('update_profile') }}" class="text-center mx-auto cursor_pointer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-16 hover:fill-black transition-all duration-100 ease-in-out">
            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
        </svg>
    </a>
</div>