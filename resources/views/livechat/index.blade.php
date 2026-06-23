<x-app-layout>
    <style>
        .chat-container { display: flex; height: 80vh; border: 1px solid #ddd; background: #fff; }
        .session-list { width: 30%; border-right: 1px solid #ddd; overflow-y: auto; }
        .session-item { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; }
        .session-item:hover { background: #f9f9f9; }
        .session-item.active { background: #e3f2fd; }
        .chat-area { width: 70%; display: flex; flex-direction: column; }
        .chat-header { padding: 15px; border-bottom: 1px solid #ddd; background: #fafafa; display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #f4f7f6; }
        .message { margin-bottom: 15px; padding: 10px 15px; border-radius: 10px; max-width: 70%; }
        .message.customer { background: #fff; border: 1px solid #ddd; align-self: flex-start; }
        .message.admin { background: #007bff; color: white; align-self: flex-end; margin-left: auto; }
        .message.ai { background: #28a745; color: white; align-self: flex-end; margin-left: auto; }
        .message.system { background: #ffc107; color: black; text-align: center; margin: 10px auto; font-size: 0.85em; }
        .chat-input { padding: 15px; border-top: 1px solid #ddd; display: flex; gap: 10px; }
        .chat-input input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; color: white; }
        .btn-takeover { background: #dc3545; }
        .btn-ai-on { background: #28a745; }
        .btn-end { background: #6c757d; }
        .btn-send { background: #007bff; }
    </style>

    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-semibold mb-4">Live Chat Dashboard (Takeover)</h2>
        <div class="chat-container rounded-lg shadow-sm">
            
            <!-- Sidebar: Daftar Kontak -->
            <div class="session-list" id="sessionList">
                <p class="p-3 text-gray-500">Memuat obrolan...</p>
            </div>

            <!-- Area Obrolan Utama -->
            <div class="chat-area" style="display: none;" id="chatArea">
                <div class="chat-header">
                    <h5 id="chatHeaderName" class="font-bold">Pilih Percakapan</h5>
                    <div class="flex gap-2">
                        <button id="btnTakeover" class="btn btn-takeover" onclick="toggleAi()">Ambil Alih (Matikan AI)</button>
                        <button id="btnEndSession" class="btn btn-end" onclick="endSession()">Akhiri Sesi</button>
                    </div>
                </div>
                
                <div class="chat-messages" id="chatMessages">
                    <!-- Pesan akan muncul di sini -->
                </div>

                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Ketik pesan balasan admin..." onkeypress="handleEnter(event)">
                    <button class="btn btn-send" onclick="sendMessage()">Kirim</button>
                </div>
            </div>

            <!-- Placeholder saat tidak ada chat dipilih -->
            <div class="chat-area" id="emptyState" style="justify-content: center; align-items: center;">
                <p class="text-gray-500">Pilih percakapan dari kiri untuk memulai.</p>
            </div>

        </div>
    </div>

    <script>
        let activeSessionId = null;
        const csrfToken = '{{ csrf_token() }}';

        // 1. Polling Daftar Sesi (Setiap 5 Detik)
        async function loadSessions() {
            try {
                const res = await fetch('/livechat/sessions');
                const sessions = await res.json();
                
                let html = '';
                sessions.forEach(session => {
                    const isActive = session.id === activeSessionId ? 'active' : '';
                    const aiBadge = session.is_ai_active ? '<span style="color:green;font-size:12px;">🤖 AI Aktif</span>' : '<span style="color:red;font-size:12px;">👤 Manual</span>';
                    
                    html += `
                        <div class="session-item ${isActive}" onclick="openChat(${session.id})">
                            <strong>${session.customer_name || 'Pelanggan'}</strong><br>
                            <small>${session.customer_phone}</small><br>
                            ${aiBadge}
                        </div>
                    `;
                });
                document.getElementById('sessionList').innerHTML = html;
            } catch (error) {
                console.error("Gagal memuat sesi", error);
            }
        }

        // 2. Buka Chat dan Load Pesan
        async function openChat(sessionId) {
            activeSessionId = sessionId;
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('chatArea').style.display = 'flex';
            
            loadSessions(); // Refresh UI sidebar
            fetchMessages(); // Ambil Pesan
        }

        // 3. Polling Pesan untuk Sesi Aktif (Setiap 3 Detik)
        async function fetchMessages() {
            if (!activeSessionId) return;
            
            try {
                const res = await fetch(`/livechat/${activeSessionId}/messages`);
                const data = await res.json();
                
                // Update Status Tombol AI
                const btnTakeover = document.getElementById('btnTakeover');
                if(data.session.is_ai_active) {
                    btnTakeover.innerText = "Ambil Alih (Matikan AI)";
                    btnTakeover.className = "btn btn-takeover";
                } else {
                    btnTakeover.innerText = "Nyalakan AI Kembali";
                    btnTakeover.className = "btn btn-ai-on";
                }

                document.getElementById('chatHeaderName').innerText = data.session.customer_name + ' - ' + data.session.customer_phone;

                let html = '';
                data.messages.forEach(msg => {
                    let msgClass = 'customer';
                    let senderName = data.session.customer_name || 'Pelanggan';
                    
                    if (msg.sender_type === 'ai') { msgClass = 'ai'; senderName = '🤖 AI'; }
                    else if (msg.sender_type === 'admin') { msgClass = 'admin'; senderName = '👤 Admin'; }
                    else if (msg.sender_type === 'system') { msgClass = 'system'; senderName = 'Sistem'; }

                    html += `
                        <div class="message ${msgClass}">
                            <div style="font-size: 0.8em; opacity: 0.7; margin-bottom: 5px;">${senderName}</div>
                            ${msg.message}
                        </div>
                    `;
                });

                const chatBox = document.getElementById('chatMessages');
                // Hanya auto-scroll jika ada pesan baru untuk mencegah scroll melompat saat admin membaca ke atas
                const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 50;
                
                chatBox.innerHTML = html;
                
                if(isScrolledToBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

            } catch (error) {
                console.error("Gagal memuat pesan", error);
            }
        }

        // 4. Kirim Pesan Admin
        async function sendMessage() {
            if (!activeSessionId) return;
            const input = document.getElementById('messageInput');
            const text = input.value.trim();
            if (!text) return;

            input.value = ''; // Kosongkan input
            
            await fetch('/livechat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ session_id: activeSessionId, message: text })
            });
            
            fetchMessages();
            // Scroll otomatis ke bawah setelah mengirim pesan
            setTimeout(() => {
                const chatBox = document.getElementById('chatMessages');
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }

        function handleEnter(e) { if (e.key === 'Enter') sendMessage(); }

        // 5. Toggle AI (Takeover)
        async function toggleAi() {
            if (!activeSessionId) return;
            if(confirm("Ubah status AI pada obrolan ini?")) {
                await fetch(`/livechat/${activeSessionId}/toggle-ai`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                fetchMessages();
                loadSessions();
            }
        }

        // 6. Akhiri Sesi
        async function endSession() {
            if (!activeSessionId) return;
            if(confirm("Yakin ingin mengakhiri sesi chat ini? AI akan dimatikan.")) {
                await fetch(`/livechat/${activeSessionId}/end`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                fetchMessages();
                loadSessions();
            }
        }

        // Jalankan Polling Otomatis
        setInterval(loadSessions, 5000); // Update Sidebar tiap 5 detik
        setInterval(fetchMessages, 3000); // Update Pesan tiap 3 detik
        
        // Load pertama kali saat halaman dibuka
        window.onload = loadSessions;
    </script>
</x-app-layout>