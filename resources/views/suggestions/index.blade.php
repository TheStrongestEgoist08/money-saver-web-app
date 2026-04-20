<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💡 AI Expense Advisor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">

                <!-- Header Section -->
                <div class="flex justify-between items-center px-8 py-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">
                            Get Smart AI Suggestions
                        </h3>
                        <p class="text-gray-500 mt-1">
                            Let AI analyze your expenses and give personalized advice
                        </p>
                    </div>

                    <!-- Flashing Button -->
                    <button id="ai-btn"
                            class="btn bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl flex items-center gap-3 font-medium transition-all duration-200 shadow-md hover:shadow-lg animate-pulse">
                        <i class="fas fa-magic"></i>
                        Get AI Suggestions
                    </button>
                </div>

                <!-- Loading Banner -->
                <div id="ai-loading" class="d-none text-center py-16 bg-indigo-50 border-b border-indigo-100">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-2xl mb-6">
                        <div class="spinner-border text-indigo-600 w-8 h-8" role="status"></div>
                    </div>
                    <p class="text-indigo-700 font-semibold text-lg">
                        AI is analyzing your expenses...
                    </p>
                    <p class="text-gray-500 text-sm mt-1">This may take a few seconds</p>
                </div>

                <!-- Result -->
                <div id="ai-result" class="p-8"></div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const aiBtn = document.getElementById('ai-btn');
            const loading = document.getElementById('ai-loading');
            const resultDiv = document.getElementById('ai-result');

            const aiUrl = "{{ route('user.suggestions.ai') }}";

            aiBtn.addEventListener('click', getAISuggestions);

            async function getAISuggestions() {
                console.clear();

                // Disable button and show "AI is analyzing"
                aiBtn.disabled = true;
                aiBtn.classList.remove('animate-pulse');
                aiBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> AI is analyzing...`;

                // Show loading
                loading.classList.remove('d-none');
                resultDiv.innerHTML = '';

                try {
                    const response = await fetch(aiUrl, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);

                    const data = await response.json();

                    if (data.success === true && data.suggestions) {

                        // Create the result container
                        resultDiv.innerHTML = `
                            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                                <div class="px-8 py-5 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <strong class="text-gray-800">AI Suggestions</strong>
                                        <span class="text-gray-400 text-sm ml-2">• ${data.period || 'Current Period'}</span>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <div id="typed-text" class="leading-relaxed text-gray-700 text-[17px] min-h-[100px]">
                                    </div>
                                </div>
                            </div>
                        `;

                        // Typing Animation
                        const typedContainer = document.getElementById('typed-text');
                        const fullText = data.suggestions;
                        let index = 0;
                        const speed = 10; // typing speed in ms (lower = faster)

                        function typeWriter() {
                            if (index < fullText.length) {
                                // Handle new lines properly
                                if (fullText.charAt(index) === '\n') {
                                    typedContainer.innerHTML += '<br><br>';
                                } else {
                                    typedContainer.innerHTML += fullText.charAt(index);
                                }
                                index++;
                                setTimeout(typeWriter, speed);
                            }
                        }

                        // Start typing after a small delay
                        setTimeout(typeWriter, 300);

                    } else {
                        // No suggestions case
                        resultDiv.innerHTML = `
                            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                                <div class="flex gap-3">
                                    <i class="fas fa-exclamation-triangle text-amber-500 text-xl mt-0.5"></i>
                                    <div>
                                        <strong class="text-amber-700">No suggestions available</strong>
                                        <p class="text-amber-600 mt-1">${data.message || 'Please try again later.'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                } catch (error) {
                    console.error('Error:', error);
                    resultDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                            <div class="flex gap-3">
                                <i class="fas fa-circle-xmark text-red-500 text-xl mt-0.5"></i>
                                <div>
                                    <strong class="text-red-700">Request Failed</strong>
                                    <p class="text-red-600 mt-1">${error.message}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } finally {
                    // Hide loading and reset button
                    loading.classList.add('d-none');

                    aiBtn.disabled = false;
                    aiBtn.innerHTML = `<i class="fas fa-magic"></i> Get AI Suggestions`;
                    aiBtn.classList.add('animate-pulse'); // restart flashing
                }
            }
        });
    </script>
</x-app-layout>
