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
    $(document).ready(function() {

        $('#ai-btn').on('click', function() {
            getAISuggestions();
        });

        function getAISuggestions() {
            const btn = $('#ai-btn');
            const loading = $('#ai-loading');
            const result = $('#ai-result');

            btn.prop('disabled', true)
               .html('<i class="fas fa-spinner fa-spin"></i> Analyzing...');

            loading.removeClass('d-none');
            result.html('');

            $.ajax({
                url: "{{ route('expenses.ai-suggestions') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        let html = `
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                                    <strong>AI Suggestions • ${response.period}</strong>
                                </div>
                                <div class="card-body p-5 leading-relaxed text-gray-700 dark:text-gray-300">
                                    ${response.suggestions.replace(/\n/g, '<br><br>')}
                                </div>
                            </div>
                        `;
                        result.html(html);
                    } else {
                        result.html(`
                            <div class="alert alert-danger">
                                ${response.message || 'Something went wrong.'}
                            </div>
                        `);
                    }
                },
                error: function() {
                    result.html(`
                        <div class="alert alert-danger">
                            Failed to connect. Please check your internet and try again.
                        </div>
                    `);
                },
                complete: function() {
                    btn.prop('disabled', false)
                       .html('<i class="fas fa-magic"></i> Get AI Suggestions');
                    loading.addClass('d-none');
                }
            });
        }

    });
    </script>
    @endpush
</x-app-layout>
