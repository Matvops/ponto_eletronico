<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private UserService $service;
    
    public function __construct(UserService $service)
    {
        $this->service = $service;
    } 

    public function update(UpdateProfileRequest $request) 
    {

        $dados = [
            'username' => $request->input('username'),
            'email' =>  $request->input('email'),
            'password' => $request->input('password')
        ];
        
        $response = $this->service->updateUserData($dados);

        if(!$response->getStatus()) 
            return back()->withInput()
                            ->with('error_update_profile', $response->getMessage());
        
        if($response->getData()) {
            Auth::logout();
            return redirect()->route('login')
                                ->with('success_update_profile', $response->getMessage());
        }
        
        if(Gate::denies('viewHomeAdmin', Auth::user()))
            return redirect()->route('home_user')
                            ->with('success_update_profile',  $response->getMessage());

        return redirect()->route('home_admin')
                            ->with('success_update_profile',  $response->getMessage());
    }
}
