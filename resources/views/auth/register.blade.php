@extends('layouts.app')

@section('title', __('Register'))

@section('page-id', 'register')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">{{ __('Register') }}</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
        
            <div class="form-control">
                <label class="label" for="name">Nombre</label>
                <input id="name" type="text" name="name" class="input input-bordered w-full" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="form-control">
                <label class="label" for="surname">Apellidos</label>
                <input id="surname" type="text" name="surname" class="input input-bordered w-full" value="{{ old('surname') }}" required>
                @error('surname')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="form-control">
                <label class="label" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" class="input input-bordered w-full" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="form-control relative">
                <label class="label" for="password">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" class="input input-bordered w-full pr-10" required>
                @error('password')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
                <button type="button" id="togglePassword" class="absolute right-3 top-10 transform -translate-y-1/4 text-gray-500">
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </button>
            </div>
              
            <div class="form-control relative">
                <label class="label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input input-bordered w-full pr-10" required>
                @error('password_confirmation')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
                <button type="button" id="togglePasswordConfirm" class="absolute right-3 top-10 transform -translate-y-1/4 text-gray-500">
                    <svg id="eyeClosedConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </button>
            </div>
        
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>        
    </div> 
@endsection
