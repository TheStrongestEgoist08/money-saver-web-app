{{-- AI Expense Advisor - ChatGPT Style (Final Fixed Version) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            💡 AI Expense Advisor
        </h2>
    </x-slot>

    <div class="flex h-[calc(100vh-4rem)] overflow-hidden bg-gray-50">

        <!-- Sidebar -->
        <div id="sidebar"
             class="w-72 lg:w-80 border-r border-gray-200 bg-white flex flex-col transition-all duration-300 fixed lg:static inset-y-0 left-0 z-50 -translate-x-full lg:translate-x-0 shadow-lg lg:shadow-none">

            <div class="p-4 border-b border-gray-200">
                <button onclick="newChat()"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 px-4 rounded-2xl flex items-center justify-center gap-2 font-medium transition-all active:scale-95">
                    <i class="fas fa-plus"></i>
                    <span>New Chat</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-3 py-2" id="chat-list">
                <!-- Populated by JS -->
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Bar -->
            <div class="h-14 border-b border-gray-200 bg-white px-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-100 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                        </svg>
                    </button>

                    <div>
                        <h3 class="font-semibold text-gray-800" id="chat-title">New Conversation</h3>
                        <p class="text-xs text-gray-500" id="chat-subtitle">Luna • Personal Finance AI</p>
                    </div>
                </div>

                <button onclick="deleteCurrentChat()" class="text-gray-500 hover:text-red-600 p-2 rounded-xl hover:bg-gray-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill text-red-600" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages"
                 class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 bg-gray-50">
                <!-- Messages populated by JS -->
            </div>

            <!-- Input Area -->
            <div class="p-4 md:p-6 bg-white border-t border-gray-200">
                <div class="max-w-3xl mx-auto">
                    <div class="relative">
                        <input id="user-message"
                               type="text"
                               class="w-full bg-gray-100 border border-gray-300 focus:border-indigo-500 focus:ring-0 rounded-3xl py-4 pl-6 pr-14 text-gray-800 placeholder-gray-500 focus:bg-white transition-all"
                               placeholder="Ask about your expenses, budget, savings...">

                        <button onclick="sendMessage()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white w-10 h-10 rounded-2xl flex items-center justify-center transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square-fill" viewBox="0 0 16 16">
                                    <path d="M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1"/>
                                </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentConversationId = null;
        let isSidebarOpen = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            isSidebarOpen = !isSidebarOpen;
            sidebar.classList.toggle('-translate-x-full', !isSidebarOpen);
        }

        async function loadConversations() {
            try {
                const response = await fetch("{{ route('user.suggestions.conversations') }}");
                const data = await response.json();
                const container = document.getElementById('chat-list');
                container.innerHTML = '';

                data.forEach(chat => {
                    const isActive = chat.id === currentConversationId ? 'bg-indigo-50 border-l-4 border-indigo-600' : 'hover:bg-gray-50';
                    container.innerHTML += `
                        <div onclick="openChat(${chat.id}); ${window.innerWidth < 1024 ? 'toggleSidebar()' : ''}"
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

        function newChat() {
            currentConversationId = null;
            document.getElementById('chat-title').textContent = 'New Conversation';
            document.getElementById('chat-messages').innerHTML = '';
            if (window.innerWidth < 1024) toggleSidebar();
            loadConversations();
        }

        async function openChat(id) {
            currentConversationId = id;
            try {
                const res = await fetch(`{{ url('/user/suggestions/conversations') }}/${id}`);
                const data = await res.json();

                document.getElementById('chat-title').textContent = data.title;

                const messagesDiv = document.getElementById('chat-messages');
                messagesDiv.innerHTML = '';

                data.messages.forEach(msg => {
                    addMessage(msg.role, msg.content, msg.id);
                });

                loadConversations();
            } catch (e) {
                console.error("Failed to open chat:", e);
            }
        }

        // Markdown Parser
        function parseMarkdown(text) {
            if (!text) return '';

            // Bold: **text** or __text__
            text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/__(.+?)__/g, '<strong>$1</strong>');

            // Italic: *text* or _text_
            text = text.replace(/\*([^\*]+?)\*/g, '<em>$1</em>');
            text = text.replace(/_([^_]+?)_/g, '<em>$1</em>');

            // Line breaks
            text = text.replace(/\n/g, '<br>');

            return text;
        }

        function addMessage(role, content, messageId = null) {
            const container = document.getElementById('chat-messages');
            const isUser = role === 'user';

            const formattedContent = isUser ? content.replace(/\n/g, '<br>') : parseMarkdown(content);

            const messageHTML = `
                <div class="group flex ${isUser ? 'justify-end' : 'justify-start'} relative items-start gap-2">
                    <div class="${isUser
                        ? 'bg-indigo-600 text-white rounded-3xl rounded-br-none'
                        : 'bg-white border border-gray-200 rounded-3xl rounded-bl-none shadow-sm'}
                        px-5 py-4 max-w-[85%] md:max-w-[75%] leading-relaxed">
                        ${formattedContent}
                    </div>

                    ${messageId ? `
                    <button onclick="deleteMessage(${messageId}); event.stopImmediatePropagation();"
                            class="mt-2 text-gray-400 hover:text-red-500 transition-all p-2 rounded-lg hover:bg-gray-100 opacity-70 hover:opacity-100">
                        <i class="fas fa-trash-alt text-base"></i>
                    </button>` : ''}
                </div>
            `;

            container.innerHTML += messageHTML;
            container.scrollTop = container.scrollHeight;
        }

        // Typing Animation with Markdown Support
        function addTypingMessage(content, messageId = null) {
            const container = document.getElementById('chat-messages');
            const tempId = 'typing-' + Date.now();

            const messageHTML = `
                <div class="group flex justify-start relative items-start gap-2" id="${tempId}">
                    <div class="bg-white border border-gray-200 rounded-3xl rounded-bl-none shadow-sm px-5 py-4 max-w-[85%] md:max-w-[75%] leading-relaxed" id="${tempId}-content">
                    </div>
                </div>
            `;

            container.innerHTML += messageHTML;
            container.scrollTop = container.scrollHeight;

            const contentElement = document.getElementById(`${tempId}-content`);
            let i = 0;
            const speed = 15;

            function type() {
                if (i < content.length) {
                    const char = content.charAt(i);
                    contentElement.innerHTML += (char === '\n') ? '<br>' : char;
                    i++;
                    container.scrollTop = container.scrollHeight;
                    setTimeout(type, speed);
                } else {
                    // Finish typing and apply markdown formatting
                    contentElement.innerHTML = parseMarkdown(content);

                    if (messageId) {
                        const messageDiv = document.getElementById(tempId);
                        messageDiv.innerHTML += `
                            <button onclick="deleteMessage(${messageId}); event.stopImmediatePropagation();"
                                    class="mt-2 text-gray-400 hover:text-red-500 transition-all p-2 rounded-lg hover:bg-gray-100 opacity-70 hover:opacity-100">
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        `;
                    }
                    document.getElementById(tempId).id = '';
                }
            }

            type();
        }

        function showTypingIndicator() {
            const container = document.getElementById('chat-messages');
            const typingHTML = `
                <div id="thinking-indicator" class="flex justify-start">
                    <div class="bg-white border border-gray-200 rounded-3xl rounded-bl-none shadow-sm px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 text-sm">Luna is thinking</span>
                            <div class="typing-dots flex gap-1">
                                <span class="dot">.</span>
                                <span class="dot">.</span>
                                <span class="dot">.</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += typingHTML;
            container.scrollTop = container.scrollHeight;
        }

        function removeTypingIndicator() {
            const typing = document.getElementById('thinking-indicator');
            if (typing) typing.remove();
        }

        async function sendMessage() {
            const input = document.getElementById('user-message');
            const message = input.value.trim();
            if (!message) return;

            addMessage('user', message);
            input.value = '';

            showTypingIndicator();

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
                removeTypingIndicator();

                if (data.success) {
                    if (data.conversation_id) currentConversationId = data.conversation_id;
                    addTypingMessage(data.response, data.message_id || null);
                    loadConversations();
                } else {
                    addMessage('assistant', `<span class="text-red-600">${data.message || 'Something went wrong'}</span>`);
                }
            } catch (error) {
                removeTypingIndicator();
                addMessage('assistant', `<span class="text-red-600">Connection failed. Please try again.</span>`);
            }
        }

        async function deleteMessage(messageId) {
            if (!confirm('Delete this message?')) return;

            try {
                const res = await fetch(`/user/suggestions/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (res.ok && currentConversationId) {
                    openChat(currentConversationId);
                }
            } catch (err) {
                alert("Error deleting message");
            }
        }

        async function deleteCurrentChat() {
            if (!currentConversationId || !confirm('Delete this entire conversation permanently?')) return;

            try {
                await fetch(`{{ url('/user/suggestions/conversations') }}/${currentConversationId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                newChat();
            } catch (e) {
                alert("Failed to delete conversation");
            }
        }

        // Keyboard Support
        document.getElementById('user-message').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Initialize
        window.onload = () => {
            loadConversations();
        };
    </script>

    <style>
        .typing-dots span {
            animation: typing 1.2s infinite;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }

        strong { font-weight: 600; }
    </style>
</x-app-layout>
