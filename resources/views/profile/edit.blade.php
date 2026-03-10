<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Edit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-gray-800">My App</div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-800 focus:outline-none">Logout</button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto mt-8 px-4">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

            @if (session('status') === 'profile-updated')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline"> Your profile has been updated.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Save Changes
                    </button>
                </div>
            </form>

            <hr class="my-8 border-gray-300">

            <h2 class="text-xl font-bold mb-4">Delete Account</h2>
            <p class="text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>

            <button x-data="{}" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                Delete Account
            </button>

            {{-- Delete Account Modal --}}
            <div id="confirm-user-deletion" x-show="open" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-8 rounded-lg shadow-md w-96">
                    <h2 class="text-lg font-bold mb-4">Are you sure you want to delete your account?</h2>
                    <p class="text-gray-700 mb-6">This action cannot be undone. Please enter your password to confirm.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter your password">
                            @error('userDeletion.password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" x-on:click="$dispatch('close-modal')" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 focus:outline-none">Cancel</button>
                            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>