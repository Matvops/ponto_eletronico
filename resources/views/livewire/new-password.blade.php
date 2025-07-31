<div class="flex justify-center items-center h-full">
       <div class="w-[40%] w-max-500 h-max-80 background-secondary-color rounded flex flex-col box-border shadow-sm">
            <h1 class="text-center mt-5 text-3xl font-medium font-inter px-1 font-inter text-gray-800">Defina uma nova senha</h1>
            <form action="{{ route('store_new_password') }}" method="POST" class="flex-1 px-6 my-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="flex flex-col gap-5">
                     <label class="flex flex-col text-white text-2xl font-medium text-shadow-md font-inter">
                        @error('password') 
                            <p>Nova senha <span class="text-red-600 inline text-sm font-normal text-shadow-xs pl-2"> {{ $message }}</span></p> 
                        @else 
                            Nova senha
                        @enderror
                        <input type="password" name="password"
                            class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded">
                    </label>

                     <label class="flex flex-col text-white text-2xl font-medium text-shadow-md font-inter">
                        @error('password_confirmation') 
                            <p>Confirme sua nova senha <span class="text-red-600 inline text-sm font-normal text-shadow-xs pl-2"> {{ $message }}</span></p> 
                        @else 
                            Confirme a senha
                        @enderror
                        <input type="password" name="password_confirmation"
                            class="mt-2 bg-white p-2 outline-0 inset-shadow-gray-700 focus:inset-shadow-xs focus:px-3 transition-all duration-100 ease-in-out text-black text-xl font-light rounded">
                    </label>
                </div>

                <div class="flex justify-between mt-4">
                    <a href="{{route('login')}}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
                        Cancelar
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

                    @error('token')
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ $message }}
                        </span>
                    @enderror

                    @if(session('error_store_new_password'))
                    <span 
                        class="text-red-600 text-center m-0 p-0 font-inter self-center"
                        x-data="{show:true}"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                    >
                    {{ session('error_store_new_password') }}
                    </span>
                @endif

                    <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
                        Confirmar
                    </button>
                </div>
            </form>
       </div>
</div>
