(function() {
    var scriptTag = document.currentScript;
    var baseUrl = scriptTag.getAttribute('data-base-url');
    var userId = scriptTag.getAttribute('data-user-id');
    var sessionId = localStorage.getItem('tera_session_id_' + userId);
    var customerName = localStorage.getItem('tera_customer_name_' + userId) || "Customer Web";
    
    // --- KONFIGURASI PYTHON ENGINE ---
    // Ganti dengan IP/Domain server Python FastAPI Kakak (port default biasanya 8000)
    var pythonEngineUrl = 'https://chatbotnew.web.id/python-api/webhook/widget';
    var teraApiKey = 'TERA_SECURE_KEY_2026_XYZ';
    
    var widgetColor = '#4F46E5';
    var greetingText = 'Halo! Ada yang bisa dibantu?';

    fetch(`${baseUrl}/api/widget/${userId}/settings`)
        .then(res => res.json())
        .then(data => {
            if(data.error) return; 
            widgetColor = data.primary_color;
            greetingText = data.greeting_text;
            renderWidget();
        }).catch(err => console.log('TERA Widget Disabled'));

    function renderWidget() {
        var style = document.createElement('style');
        style.innerHTML = `
            #tera-widget-container { position: fixed; bottom: 20px; right: 20px; z-index: 999999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            #tera-fab { width: 60px; height: 60px; border-radius: 50%; background: ${widgetColor}; color: white; border: none; cursor: pointer; box-shadow: 0 6px 16px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; font-size: 28px; transition: transform 0.2s ease, box-shadow 0.2s ease;}
            #tera-fab:hover { transform: scale(1.08); box-shadow: 0 8px 20px rgba(0,0,0,0.25); }
            #tera-chat-box { width: 360px; height: 550px; max-height: 80vh; background: #ffffff; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; position: absolute; bottom: 85px; right: 0; border: 1px solid #f0f0f0; }
            #tera-header { background: ${widgetColor}; color: white; padding: 18px 20px; font-weight: bold; font-size: 16px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10; }
            #tera-body { flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 12px; }
            #tera-footer { border-top: 1px solid #eaeaea; padding: 12px 16px; background: white; display: flex; align-items: center; }
            
            /* -- FORCE WARNA TEKS JADI HITAM / GELAP -- */
            #tera-input { flex: 1; border: 1px solid #d1d5db; border-radius: 24px; padding: 12px 16px; outline: none; font-size: 14px; background: #f9fafb; color: #111827 !important; transition: border-color 0.2s; }
            #tera-input::placeholder { color: #9ca3af !important; }
            #tera-input:focus { border-color: ${widgetColor}; background: white; }
            
            #tera-send { background: ${widgetColor}; color: white; border: none; border-radius: 50%; width: 40px; height: 40px; margin-left: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: opacity 0.2s; }
            #tera-send:hover { opacity: 0.9; }
            #tera-send:disabled { background: #d1d5db; cursor: not-allowed; }
            
            .tera-msg { max-width: 85%; padding: 12px 16px; font-size: 14px; line-height: 1.5; word-wrap: break-word; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
            .tera-msg.customer { background: ${widgetColor}; color: white !important; align-self: flex-end; border-radius: 18px 18px 4px 18px; }
            .tera-msg.ai, .tera-msg.admin { background: white; border: 1px solid #f0f0f0; color: #374151 !important; align-self: flex-start; border-radius: 18px 18px 18px 4px; }
            
            .tera-greeting { background: white; padding: 16px; border-radius: 12px; text-align: center; font-size: 14px; color: #4b5563 !important; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px; line-height: 1.5; }
            .tera-form-group { margin-bottom: 14px; text-align: left; }
            .tera-form-label { display: block; font-size: 12px; font-weight: bold; color: #6b7280 !important; margin-bottom: 6px; }
            
            /* -- FORCE WARNA FORM INPUT JADI HITAM -- */
            .tera-form-input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; box-sizing: border-box; font-size: 14px; color: #111827 !important; background: white; outline: none; transition: border-color 0.2s; }
            .tera-form-input::placeholder { color: #9ca3af !important; }
            .tera-form-input:focus { border-color: ${widgetColor}; box-shadow: 0 0 0 3px rgba(0,0,0,0.05); }
            
            .tera-btn { width: 100%; padding: 14px; background: ${widgetColor}; color: white !important; border: none; border-radius: 10px; font-weight: bold; font-size: 15px; cursor: pointer; transition: opacity 0.2s; margin-top: 5px; }
            .tera-btn:hover { opacity: 0.9; }
            
            /* Typing Indicator */
            .typing-indicator { display: flex; gap: 4px; align-items: center; padding: 12px 16px; background: white; border: 1px solid #f0f0f0; border-radius: 18px 18px 18px 4px; align-self: flex-start; width: fit-content;}
            .typing-dot { width: 6px; height: 6px; background: #cbd5e1; border-radius: 50%; animation: typing 1.4s infinite ease-in-out; }
            .typing-dot:nth-child(1) { animation-delay: 0s; }
            .typing-dot:nth-child(2) { animation-delay: 0.2s; }
            .typing-dot:nth-child(3) { animation-delay: 0.4s; }
            @keyframes typing { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); background: ${widgetColor}; } }
        `;
        document.head.appendChild(style);

        var container = document.createElement('div');
        container.id = 'tera-widget-container';
        container.innerHTML = `
            <div id="tera-chat-box">
                <div id="tera-header">
                    <div><span style="font-size:18px; margin-right:8px;">👋</span> Customer Service</div>
                    <button id="tera-close" style="background:none; border:none; color:white; cursor:pointer; font-size:20px;">×</button>
                </div>
                <div id="tera-body"></div>
                <div id="tera-footer" style="display: none;">
                    <input type="text" id="tera-input" placeholder="Ketik pesan di sini..." autocomplete="off">
                    <button id="tera-send"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></button>
                </div>
            </div>
            <button id="tera-fab">💬</button>
        `;
        document.body.appendChild(container);

        var chatBox = document.getElementById('tera-chat-box');
        var teraBody = document.getElementById('tera-body');
        var teraFooter = document.getElementById('tera-footer');
        var teraInput = document.getElementById('tera-input');
        var sendBtn = document.getElementById('tera-send');

        document.getElementById('tera-fab').onclick = function() {
            chatBox.style.display = 'flex';
            document.getElementById('tera-fab').style.display = 'none';
            checkSession();
        };
        
        document.getElementById('tera-close').onclick = function() {
            chatBox.style.display = 'none';
            document.getElementById('tera-fab').style.display = 'flex';
        };

        function checkSession() {
            if(!sessionId) {
                teraBody.innerHTML = `
                    <div class="tera-greeting">${greetingText}</div>
                    <form id="tera-form">
                        <div class="tera-form-group">
                            <label class="tera-form-label">NAMA LENGKAP</label>
                            <input type="text" id="t-name" class="tera-form-input" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="tera-form-group">
                            <label class="tera-form-label">NOMOR WHATSAPP</label>
                            <input type="text" id="t-phone" class="tera-form-input" placeholder="Contoh: 081234567890" required>
                        </div>
                        <button type="submit" class="tera-btn">Mulai Chat Sekarang</button>
                    </form>
                `;
                document.getElementById('tera-form').onsubmit = function(e) {
                    e.preventDefault();
                    var n = document.getElementById('t-name').value;
                    var p = document.getElementById('t-phone').value;
                    customerName = n;
                    
                    teraBody.innerHTML = `
                        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:#6b7280;">
                            <svg class="animate-spin" style="width:30px; height:30px; margin-bottom:10px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="${widgetColor}" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Menghubungkan...</span>
                        </div><style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
                    `;
                    
                    // 1. Tembak Laravel untuk membuat sesi obrolan (Generate session_id)
                    fetch(`${baseUrl}/api/widget/start`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({user_id: userId, name: n, phone: p})
                    })
                    .then(res => {
                        if(!res.ok) throw new Error('API Error ' + res.status);
                        return res.json();
                    })
                    .then(d => {
                        sessionId = d.session_id;
                        localStorage.setItem('tera_session_id_' + userId, sessionId);
                        localStorage.setItem('tera_customer_name_' + userId, n);
                        teraFooter.style.display = 'flex';
                        loadMessages();
                        setInterval(loadMessages, 5000); // Polling pesan tiap 5 detik
                    })
                    .catch(err => {
                        console.error('Widget Error:', err);
                        teraBody.innerHTML = `<div style="text-align:center; padding:20px;"><div style="color:#ef4444; font-size:40px; margin-bottom:10px;">⚠️</div><h4 style="color:#111827 !important; font-weight:bold; margin-bottom:5px;">Koneksi Gagal</h4><p style="color:#6b7280 !important; font-size:12px; line-height:1.5;">Server tidak merespons. Silakan *Refresh* halaman web ini (F5) dan coba lagi.</p></div>`;
                    });
                };
            } else {
                teraFooter.style.display = 'flex';
                loadMessages();
                if(!window.pollInterval) window.pollInterval = setInterval(loadMessages, 5000);
            }
        }

        function loadMessages() {
            // Fetch histori chat dari Laravel (karena database ada di Laravel)
            fetch(`${baseUrl}/api/widget/${sessionId}/messages`)
                .then(res => res.json())
                .then(msgs => {
                    // Cek apakah ada div typing indicator
                    var typingDiv = document.getElementById('tera-typing');
                    var isTyping = typingDiv !== null;
                    
                    teraBody.innerHTML = '';
                    if(msgs.length === 0 && !isTyping) {
                        teraBody.innerHTML = `<div style="text-align:center; color:#9ca3af; font-size:13px; margin-top:20px;">Belum ada pesan. Ketik pesan pertama Anda di bawah.</div>`;
                        return;
                    }
                    
                    msgs.forEach(m => {
                        var div = document.createElement('div');
                        div.className = 'tera-msg ' + m.sender_type;
                        div.innerText = m.message;
                        teraBody.appendChild(div);
                    });
                    
                    // Kembalikan typing indicator jika sebelumnya ada
                    if(isTyping) {
                        teraBody.appendChild(typingDiv);
                    }
                    
                    teraBody.scrollTop = teraBody.scrollHeight;
                });
        }

        sendBtn.onclick = sendMsg;
        teraInput.onkeypress = function(e) { if(e.key === 'Enter') sendMsg(); };

        function sendMsg() {
            var txt = teraInput.value.trim();
            if(!txt) return;
            
            teraInput.value = '';
            teraInput.disabled = true;
            sendBtn.disabled = true;
            
            // 1. Simpan pesan customer ke DB Laravel dulu
            fetch(`${baseUrl}/api/widget/save-customer-message`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({session_id: sessionId, message: txt})
            }).then(() => {
                // Render teks customer ke UI
                var div = document.createElement('div');
                div.className = 'tera-msg customer';
                div.innerText = txt;
                teraBody.appendChild(div);
                
                // Munculkan animasi typing AI
                var typingHtml = document.createElement('div');
                typingHtml.id = 'tera-typing';
                typingHtml.className = 'typing-indicator';
                typingHtml.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
                teraBody.appendChild(typingHtml);
                teraBody.scrollTop = teraBody.scrollHeight;
    
                // 2. Tembak AI Python Engine (Bukan ke Laravel lagi)
                return fetch(pythonEngineUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'x-tera-api-key': teraApiKey
                    },
                    body: JSON.stringify({
                        session_id: sessionId, 
                        message: txt,
                        customer_name: customerName
                    })
                });
            })
            .then(res => res.json())
            .then(data => {
                teraInput.disabled = false;
                sendBtn.disabled = false;
                teraInput.focus();
                
                var typingDiv = document.getElementById('tera-typing');
                if(typingDiv) typingDiv.remove();
                
                if(data.status === 'success') {
                    // Render balasan AI langsung ke UI (Database sudah disimpan oleh Python via API Laravel)
                    var divAi = document.createElement('div');
                    divAi.className = 'tera-msg ai';
                    divAi.innerText = data.reply_text;
                    teraBody.appendChild(divAi);
                    teraBody.scrollTop = teraBody.scrollHeight;
                }
            })
            .catch(err => {
                console.error("Gagal terhubung ke Python AI Engine", err);
                teraInput.disabled = false;
                sendBtn.disabled = false;
                var typingDiv = document.getElementById('tera-typing');
                if(typingDiv) typingDiv.remove();
            });
        }
    }
})();