
{{-- AI Expense Advisor Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl md:text-3xl text-gray-800 tracking-tight">
            💡 AI Expense Advisor
        </h2>
    </x-slot>

    <div class="flex h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Sidebar -->
        <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
            <div class="p-4 border-b bg-white">
                <button onclick="newChat()"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-2xl flex items-center justify-center gap-2 font-medium transition-all">
                    <i class="fas fa-plus"></i> New Chat
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-3 py-2" id="chat-list">
                <!-- Chat list populated by JS -->
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="p-5 border-b border-gray-200 bg-white flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800" id="chat-title">New Conversation</h3>
                    <p class="text-sm text-gray-500" id="chat-subtitle">Luna • Personal Finance Advisor</p>
                </div>
                <button onclick="deleteCurrentChat()"
                        class="text-red-500 hover:text-red-600 p-2 rounded-xl hover:bg-red-50 transition-all">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50">
                <!-- Messages appear here -->
            </div>

            <!-- Input Area -->
            <div class="p-6 border-t border-gray-200 bg-white">
                <div class="flex gap-3">
                    <input id="user-message"
                           type="text"
                           class="flex-1 border border-gray-300 rounded-3xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1"
                           placeholder="Ask anything about your expenses, savings, or budget...">
                    <button onclick="sendMessage()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 rounded-3xl transition-all">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentConversationId = null;

        // Load all conversations
        async function loadConversations() {
            try {
                const response = await fetch("{{ route('user.suggestions.conversations') }}");
                if (!response.ok) throw new Error('Failed to load chats');

                const data = await response.json();
                const container = document.getElementById('chat-list');
                container.innerHTML = '';

                data.forEach(chat => {
                    const isActive = chat.id === currentConversationId ? 'bg-white shadow-sm border border-indigo-200' : 'hover:bg-white';
                    container.innerHTML += `
                        <div onclick="openChat(${chat.id})"
                             class="p-4 mx-2 my-1 rounded-2xl cursor-pointer transition-all ${isActive}">
                            <div class="font-medium text-gray-800 line-clamp-1">${chat.title}</div>
                            <div class="text-xs text-gray-500 mt-1">${new Date(chat.updated_at).toLocaleDateString()}</div>
                        </div>
                    `;
                });
            } catch (e) {
                console.error("Load conversations failed:", e);
            }
        }

        // Start new chat
        function newChat() {
            currentConversationId = null;
            document.getElementById('chat-title').textContent = 'New Conversation';
            document.getElementById('chat-messages').innerHTML = '';
            loadConversations();
        }

        // Open existing chat
        async function openChat(id) {
            currentConversationId = id;

            try {
                const res = await fetch(`{{ url('/user/suggestions/conversations') }}/${id}`);
                const data = await res.json();

                document.getElementById('chat-title').textContent = data.title;

                const messagesDiv = document.getElementById('chat-messages');
                messagesDiv.innerHTML = '';

                data.messages.forEach(msg => {
                    addMessage(msg.role, msg.content);
                });

                loadConversations();
            } catch (e) {
                console.error("Failed to open chat:", e);
            }
        }

        // Add message to UI
        function addMessage(role, content) {
            const container = document.getElementById('chat-messages');
            const isUser = role === 'user';

            container.innerHTML += `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                    <div class="${isUser ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200'}
                        rounded-3xl px-5 py-4 max-w-[75%] leading-relaxed">
                        ${content.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
            container.scrollTop = container.scrollHeight;
        }

        // Send message
        async function sendMessage() {
            const input = document.getElementById('user-message');
            const message = input.value.trim();
            if (!message) return;

            addMessage('user', message);
            input.value = '';

            const payload = {
                message: message,
                conversation_id: currentConversationId,
                period: 'month'
            };

            try {
                const response = await fetch("{{ route('user.suggestions.ai') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    addMessage('assistant', data.response);
                    if (data.conversation_id) {
                        currentConversationId = data.conversation_id;
                    }
                    loadConversations();
                } else {
                    addMessage('assistant', `<span class="text-red-600">${data.message || 'Something went wrong'}</span>`);
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                addMessage('assistant', `<span class="text-red-600">Connection failed. Please check your internet and try again.</span>`);
            }
        }

        // Delete current chat
        async function deleteCurrentChat() {
            if (!currentConversationId || !confirm('Delete this conversation permanently?')) return;

            try {
                await fetch(`{{ url('/user/suggestions/conversations') }}/${currentConversationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                newChat();
            } catch (e) {
                alert("Failed to delete conversation");
            }
        }

        // Enter key support
        document.getElementById('user-message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        // Initialize on page load
        window.onload = function() {
            loadConversations();
        };
    </script>
</x-app-layout>
