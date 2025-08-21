<div class="h-[100vh] flex">

    <x-left-menu />

    <main class="mx-auto w-[60%] font-inter gap-12 my-24">
        <header class="flex justify-between px-4 py-4 background-secondary-color mb-12 rounded-sm shadow-sm">
            <div>
                <select class="text-white cursor-pointer background-primary-color hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-50 font-medium rounded-lg text-sm px-2 py-2.5 text-center inline-flex items-center dark:bg-blue-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <option>Filtro</option>
                    <option class="background-primary-color cursor-pointer" wire:click="asc">A-Z</option>
                    <option class="background-primary-color cursor-pointer" wire:click="desc">Z-A</option>
                </select>
            </div>

            <div class="flex border-b-1 pb-1">
                <input wire:model.live="query" type="text" class="outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </header>

        <div class="background-secondary-color rounded-sm shadow-sm">
            <section class="p-0 m-0">
                <ul class="p-0 m-0">
                    @foreach ($users as $user)
                        <li class="flex items-center justify-between py-2 px-4 border-b-1 border-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="size-16">
                                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                            </svg>
                            {{$user->username}}

                            <div class="flex items-center gap-2">

                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('delete_user') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input name="id" type="hidden" value="{{ Crypt::encrypt($user->usr_id) }}">
                                    <button type="submit" class="m-0 p-0 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-8 hover:stroke-gray-600 transition-all duration-100 ease">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            @if(count($users) > 0)
                <footer class="background-primary-color py-2 text-center">
                    {{ $users->links() }}
                </footer>
            @else 
                <p class="py-2 text-center text-gray-700 font-light">Usuário não encontrado</p>
            @endif
        </div>
    </main>
</div>
