<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Dịch vụ được đặt nhiều
        </x-slot>

        <div class="space-y-3">
            @forelse ($this->getServices() as $service)
                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-700">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-medium text-gray-950 dark:text-white">
                            {{ $service['name'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $service['count'] }} lượt đặt
                        </div>
                    </div>
                    <div class="shrink-0 text-sm font-semibold text-amber-700 dark:text-amber-300">
                        {{ $service['revenue'] }}
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 px-3 py-6 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    Chưa có dữ liệu booking để thống kê.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
