@extends('layouts.app')

@section('title', 'Login - POS Toko Kelontong')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-500 rounded-xl mx-auto flex items-center justify-center text-2xl mb-4">🏪</div>
            <h1 class="text-2xl font-bold text-gray-900">POS Toko Kelontong</h1>
            <p class="text-gray-500 mt-1">Silakan login untuk melanjutkan</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                    placeholder="admin@pos.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                    placeholder="password">
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition duration-200">
                Masuk
            </button>
        </form>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
            <p class="font-medium mb-2">🔑 Akun Demo:</p>
            <div class="space-y-1">
                <p><span class="font-mono bg-gray-200 px-1 rounded">admin@pos.com</span> / password (Admin)</p>
                <p><span class="font-mono bg-gray-200 px-1 rounded">kasir@pos.com</span> / password (Kasir)</p>
            </div>
        </div>
    </div>
</div>
@endsection

