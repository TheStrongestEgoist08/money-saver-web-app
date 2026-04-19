<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            💡 AI Expense Advisor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Get Smart AI Suggestions
                    </h3>
                    <button id="ai-btn"
                            class="btn btn-primary px-5 py-2 flex items-center gap-2">
                        <i class="fas fa-magic"></i>
                        Get AI Suggestions
                    </button>
                </div>

                <!-- Loading -->
                <div id="ai-loading" class="d-none text-center py-8">
                    <div class="spinner-border text-blue-600 mx-auto" role="status"></div>
                    <p class="mt-3 text-gray-600 dark:text-gray-400">
                        AI is analyzing your expenses...
                    </p>
                </div>

                <!-- Result -->
                <div id="ai-result"></div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const aiBtn = document.getElementById('ai-btn');
        const loading = document.getElementById('ai-loading');
        const result = document.getElementById('ai-result');

        aiBtn.addEventListener('click', getAISuggestions);

        async function getAISuggestions() {
            // Disable button and show loading
            aiBtn.disabled = true;
            aiBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';
            loading.classList.remove('d-none');
            result.innerHTML = '';

            try {
                const response = await fetch("{{ route('user.suggestions.ai') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    result.innerHTML = `
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                                <strong>AI Suggestions • ${data.period}</strong>
                            </div>
                            <div class="card-body p-5 leading-relaxed text-gray-700 dark:text-gray-300">
                                ${data.suggestions.replace(/\n/g, '<br><br>')}
                            </div>
                        </div>
                    `;
                } else {
                    result.innerHTML = `
                        <div class="alert alert-danger">
                            ${data.message || 'Something went wrong.'}
                        </div>
                    `;
                }

            } catch (error) {
                result.innerHTML = `
                    <div class="alert alert-danger">
                        Failed to connect. Please check your internet and try again.
                    </div>
                `;
            } finally {
                // Reset button
                aiBtn.disabled = false;
                aiBtn.innerHTML = '<i class="fas fa-magic"></i> Get AI Suggestions';
                loading.classList.add('d-none');
            }
        }
    });
    </script>
    @endpush
</x-app-layout>
