<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін-панель - Облік Техніки</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<div class="flex flex-col md:flex-row min-h-screen">

    <!-- Sidebar -->
    <div class="bg-gray-800 shadow-xl h-16 fixed bottom-0 md:relative md:h-screen z-10 w-full md:w-48">
        <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
            <div class="md:mt-4 text-center">
                <h1 class="text-white text-xl font-bold hidden md:block">Інвентар МВКТЗ</h1>
            </div>
            <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">
                <li class="mr-3 flex-1">
                    <a href="{{ route('admin.equipment') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 {{ request()->routeIs('admin.equipment') ? 'border-blue-600' : 'border-gray-800' }} hover:border-pink-500">
                        <i class="fas fa-tasks pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Обладнання</span>
                    </a>
                </li>
                <li class="mr-3 flex-1">
                    <a href="{{ route('admin.employees') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 {{ request()->routeIs('admin.employees') ? 'border-blue-600' : 'border-gray-800' }} hover:border-purple-500">
                        <i class="fa fa-users pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Співробітники</span>
                    </a>
                </li>
                <li class="mr-3 flex-1">
                    <a href="{{ route('admin.categories') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 {{ request()->routeIs('admin.categories') ? 'border-blue-600' : 'border-gray-800' }} hover:border-green-500">
                        <i class="fa fa-folder pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Категорії</span>
                    </a>
                </li>
                <li class="mr-3 flex-1">
                    <a href="{{ route('admin.types') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 {{ request()->routeIs('admin.types') ? 'border-blue-600' : 'border-gray-800' }} hover:border-red-500">
                        <i class="fa fa-tag pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Типи</span>
                    </a>
                </li>
                <li class="mr-3 flex-1 mt-4">
                    <form method="POST" action="{{ route('logout') }}" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline border-t border-gray-700">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 w-full text-left">
                            <i class="fa fa-sign-out-alt pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base block md:inline-block">Вихід</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="bg-gray-800 pt-3">
            <div class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                <h3 class="font-bold pl-2">Панель керування</h3>
            </div>
        </div>

        <div class="flex flex-wrap m-4">
            {{ $slot }}
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
