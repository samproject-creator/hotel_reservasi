<div class="bg-white dark:bg-slate-850 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-slate-800 overflow-hidden {{ $attributes->get('class') }}">
    @if(isset($title))
        <div class="px-8 py-6 border-b border-gray-50 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">{{ $title }}</h3>
            @if(isset($action))
                {{ $action }}
            @endif
        </div>
    @endif
    <div class="p-8">
        {{ $slot }}
    </div>
</div>
