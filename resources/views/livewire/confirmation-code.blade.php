<div class="flex justify-center items-center h-full">
       <div class="w-[40%] w-max-500 h-max-80 background-secondary-color rounded flex flex-col box-border shadow-sm">
            <h1 class="text-center mt-5 text-xl font-medium font-inter px-1">Confirme o código enviado para <span class="font-semibold">{{ $email }}</span></h1>
            <form action="{{ route('confirm_code') }}" method="POST" class="flex-1 px-6 my-6">
                @csrf

                <input type="hidden" name="email" value="{{$email}}">
                <div class="flex gap-5 items-center justify-center">
                    <input type="number" max="9" name="numberOne" class="w-[20%] max-w-10 bg-white py-3 rounded outline-0 text-xl text-center">
                    <input type="number" max="9" name="numberTwo" class="w-[20%] max-w-10 bg-white py-3 rounded outline-0 text-xl text-center">
                    <input type="number" max="9" name="numberTree" class="w-[20%] max-w-10 bg-white py-3 rounded outline-0 text-xl text-center">
                    <input type="number" max="9"name="numberFour" class="w-[20%] max-w-10 bg-white py-3 rounded outline-0 text-xl text-center">
                </div>

                <div class="flex justify-between pt-4">
                    <a href="{{ route('forgot_password') }}" 
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
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

                    @error('numberOne')
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ $message }}
                        </span>
                    @enderror

                    @error('numberTwo')
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ $message }}
                        </span>
                    @enderror

                    @error('numberTree')
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ $message }}
                        </span>
                    @enderror

                    @error('numberFour')
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ $message }}
                        </span>
                    @enderror

                    @if(session('error_confirm_code'))
                        <span 
                            class="text-red-600 text-center m-0 p-0 font-inter self-center"
                            x-data="{show:true}"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                        >
                        {{ session('error_confirm_code') }}
                        </span>
                    @endif 

                    <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow cursor-pointer">
                        Confirmar
                    </button>
                </div>
            </form>
       </div>
</div>
