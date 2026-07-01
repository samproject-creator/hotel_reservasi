<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ $attributes->get('class') }}">
    @if(isset($title))
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">{{ $title }}</h3>
            @if(isset($action))
                {{ $action }}
            @endif
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
