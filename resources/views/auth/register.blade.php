{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<div class="login-container">
    <div class="login-box">
        <h2>Đăng ký</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name" :value="__('Name')">Nhập tên của bạn</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus
                    autocomplete="name" />
            </div>
            <div class="form-group">
                <label for="email" :value="__('Email')">Nhập email của bạn</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                    autocomplete="username" />
            </div>
            <div class="form-group">
                <label for="password" :value="__('Password')">Nhập password của bạn</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label for="password_confirmation" :value="__('Confirm Password')">Xác nhận password của bạn</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password">
            </div>
            <button type="submit">Đăng ký</button>
        </form>
    </div>
</div>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-box {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 28rem;
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 1.5rem;
        font-size: 1.875rem;
        color: #1f2937;
    }

    .form-group {
        margin-bottom: 1rem;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.25rem;
        color: #374151;
    }

    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        align-items: center;
    }

    .form-options label {
        display: flex;
        align-items: center;
        color: #4b5563;
        font-size: 0.875rem;
    }

    .form-options input {
        margin-right: 0.5rem;
    }

    .form-options a {
        color: #4b5563;
        text-decoration: underline;
    }

    .form-options a:hover {
        color: #1f2937;
    }

    button {
        width: 100%;
        padding: 0.75rem;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        color: #1f2937;
        cursor: pointer;
    }

    button:hover {
        background-color: #f3f4f6;
    }

    .register-link {
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #4b5563;
    }

    .register-link a {
        color: #4f46e5;
        text-decoration: underline;
    }

    .register-link a:hover {
        color: #6366f1;
    }

    @media (max-width: 640px) {
        .login-box {
            max-width: 90%;
        }
    }
</style>
