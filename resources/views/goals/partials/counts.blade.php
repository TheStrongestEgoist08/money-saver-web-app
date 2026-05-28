
{{-- Counts Panel --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">
                🚀 Active
            </p>

            <h3 class="text-3xl font-bold text-gray-800">
                {{ $goalCounts['active'] ?? 0 }}
            </h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">
                ✅ Completed
            </p>

            <h3 class="text-3xl font-bold text-gray-800">
                {{ $goalCounts['completed'] ?? 0 }}
            </h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">
                ❌ Cancelled
            </p>

            <h3 class="text-3xl font-bold text-gray-800">
                {{ $goalCounts['cancelled'] ?? 0 }}
            </h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">
                ⚠️ Failed
            </p>

            <h3 class="text-3xl font-bold text-gray-800">
                {{ $goalCounts['failed'] ?? 0 }}
            </h3>
        </div>
    </div>
</div>
