<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-surface-800 border-r border-white/5 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex md:flex-col md:shrink-0">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/5">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg shadow-brand-500/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <h1 class="text-sm font-bold text-white tracking-wide">Інвентар</h1>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest">МВКТЗ</p>
        </div>
        <button @click="sidebarOpen = false" class="ml-auto md:hidden text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Основне</p>

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.dashboard') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Дашборд
        </a>

        <a href="{{ route('admin.equipment') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.equipment') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Обладнання
        </a>

        <a href="{{ route('admin.assets') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.assets') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Активи
        </a>

        <a href="{{ route('admin.software-licenses') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.software-licenses') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Ліцензії ПЗ
        </a>

        <a href="{{ route('admin.low-value-materials') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.low-value-materials') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Малоцінні матеріали
        </a>

        <a href="{{ route('admin.employees') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.employees') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Співробітники
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Журнали</p>

        <a href="{{ route('admin.movements') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->is('admin/movements*') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Переміщення
        </a>


        <a href="{{ route('admin.maintenance-logs') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.maintenance-logs') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Журнал ТО
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Договори</p>

        <a href="{{ route('admin.contracts') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.contracts') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Договори
        </a>

        <a href="{{ route('admin.suppliers') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.suppliers') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Постачальники
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Довідники</p>

        <a href="{{ route('admin.categories') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.categories') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Категорії
        </a>

        <a href="{{ route('admin.types') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.types') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Моделі техніки
        </a>

        <a href="{{ route('admin.base-components') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.base-components') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Базові компоненти
        </a>


        <a href="{{ route('admin.locations') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.locations') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Кабінети та локації
        </a>


        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Нові довідники</p>

        <a href="{{ route('admin.brands') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.brands') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Бренди
        </a>

        <a href="{{ route('admin.departments') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.departments') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Відділи
        </a>

        <a href="{{ route('admin.organizations') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.organizations') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Організації
        </a>

        <a href="{{ route('admin.retirement-acts') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.retirement-acts') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Акти списання техніки
        </a>

        <a href="{{ route('admin.write-off-acts') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.write-off-acts') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Акти списання малоцінки
        </a>

        <a href="{{ route('admin.attribute-dictionaries') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.attribute-dictionaries') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Словник атрибутів
        </a>

        <a href="{{ route('admin.supplier-types') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.supplier-types') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Типи постачальників
        </a>

        <a href="{{ route('admin.computer-software') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.computer-software') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            Програмне забезпечення
        </a>

        <a href="{{ route('admin.employee-phones') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.employee-phones') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            Телефони співробітників
        </a>

        <a href="{{ route('admin.item-properties') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.item-properties') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Динамічні властивості
        </a>

        <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Система</p>

        <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.users') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Користувачі
        </a>

        <a href="{{ route('admin.system-errors') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.system-errors') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Помилки сайту
        </a>

        @if(auth()->check() && strtolower(auth()->user()->login) === 'admin')
            <p class="px-3 mt-4 mb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Адмiнiстратор</p>
            
            <a href="{{ route('admin.history') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('admin.history') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Історія та Аудит
            </a>
        @endif

    </nav>

    <!-- User Info & Logout -->
    <div class="px-3 py-4 border-t border-white/5">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xs font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->login ?? '' }}</p>
            </div>
            <div x-data="{ open: false, themes: ['indigo','sky','emerald','rose','amber','violet','cyan','orange'], currentTheme: localStorage.getItem('theme') || 'indigo' }" class="relative mr-2">
                <button @click="open = !open" @click.outside="open = false" class="text-gray-500 hover:text-brand-400 transition-colors" title="Змінити тему">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     style="display: none;"
                     class="absolute bottom-full mb-2 right-0 w-40 p-2 rounded-xl bg-surface-800 border border-white/10 shadow-xl z-50">
                    <p class="text-[10px] text-gray-400 mb-2 px-1 font-medium tracking-wide uppercase">Колір теми</p>
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="theme in themes" :key="theme">
                            <button @click="window.setTheme(theme); currentTheme = theme; open = false"
                                    :title="theme"
                                    class="w-6 h-6 rounded-full relative focus:outline-none transition-transform hover:scale-110"
                                    :class="{
                                        'bg-indigo-500': theme === 'indigo',
                                        'bg-sky-500': theme === 'sky',
                                        'bg-emerald-500': theme === 'emerald',
                                        'bg-rose-500': theme === 'rose',
                                        'bg-amber-500': theme === 'amber',
                                        'bg-violet-500': theme === 'violet',
                                        'bg-cyan-500': theme === 'cyan',
                                        'bg-orange-500': theme === 'orange',
                                        'ring-2 ring-white ring-offset-2 ring-offset-surface-800 scale-110': currentTheme === theme
                                    }">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors" title="Вихід">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
