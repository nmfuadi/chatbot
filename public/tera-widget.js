(function() {
    // Ambil Script Tag Saat Ini untuk mendapatkan ID User dan Base URL
    var scriptTag = document.currentScript;
    var baseUrl = scriptTag.getAttribute('data-base-url');
    var userId = scriptTag.getAttribute('data-user-id');
    var sessionId = localStorage.getItem('tera_session_id_' + userId);
    
    var widgetColor = '#4F46E5';
    var greetingText = 'Halo! Ada yang bisa dibantu?';

    // 1. Fetch Setting dari Server
    fetch(`${baseUrl}/api/widget/${userId}/settings`)
        .then(res => res.json())
        .then(data => {
            if(data.error) return; // Jangan render jika mati
            widgetColor = data.primary_color;
            greetingText = data.greeting_text;
            renderWidget();
        }).catch(err => console.log('TERA Widget Disabled'));

    function renderWidget() {
        // --- Buat CSS Injector ---
        var style = document.createElement('style');
        style.innerHTML = `
            #tera-widget-container { position: fixed; bottom: 20px; right: 20px; z-index: 999999; font-family: sans-serif; }
            #tera-fab { width: 60px; height: 60px; border-radius: 50%; background: ${widgetColor}; color: white; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; font-size: 24px; transition: transform 0.2s;}
            #tera-fab:hover { transform: scale(1.05); }
            #tera-chat-box { width: 350px; height: 500px; background: white; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; position: absolute; bottom: 80px; right: 0; }
            #tera-header { background: ${widgetColor}; color: white; padding: 16px; font-weight: bold; text-align: center; }
            #tera-body { flex: 1; padding: 16px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px; }
            #tera-footer { border-top: 1px solid #eee; padding: 12px; background: white; display: flex; }
            #tera-input { flex: 1; border: 1px solid #ddd; border-radius: 20px; padding: 8px 16px; outline: none; font-size: 14px; }
            #tera-send { background: ${widgetColor}; color: white; border: none; border-radius: 50%; width: 36px; height: 36px; margin-left: 8px; cursor: pointer; }
            .tera-msg { max-width: 80%; padding: 10px 14px; border-radius: 16px; font-size: 14px; line-height: 1.4; word-wrap: break-word;}
            .tera-msg.customer { background: ${widgetColor}; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
            .tera-msg.ai, .tera-msg.admin { background: white; border: 1px solid #eee; color: #333; align-self: flex-start; border-bottom-left-radius: 4px; }
            .tera-form-input { w-full; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px; width: 100%; box-sizing: border-box;}
            .tera-btn { width: 100%; padding: 10px; background: ${widgetColor}; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        `;
        document.head.appendChild(style);

        // --- Buat Elemen HTML ---
        var container = document.createElement('div');
        container.id = 'tera-widget-container';
        container.innerHTML = `
            <div id="tera-chat-box">
                <div id="tera-header">Customer Service</div>
                <div id="tera-body"></div>
                <div id="tera-footer" style="display: none;">
                    <input type="text" id="tera-input" placeholder="Ketik pesan..." autocomplete="off">
                    <button id="tera-send">➤</button>
                </div>
            </div>
            <button id="tera-fab">💬</button>
        `;
        document.body.appendChild(container);

        var chatBox = document.getElementById('tera-chat-box');
        var teraBody = document.getElementById('tera-body');
        var teraFooter = document.getElementById('tera-footer');
        var teraInput = document.getElementById('tera-input');

        // Logic Toggle
        document.getElementById('tera-fab').onclick = function() {
            chatBox.style.display = chatBox.style.display === 'flex' ? 'none' : 'flex';
            if(chatBox.style.display === 'flex') checkSession();
        };

        function checkSession() {
            if(!sessionId) {
                // Tampilkan Form
                teraBody.innerHTML = `
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align:center; font-size:14px; border:1px solid #eee;">${greetingText}</div>
                    <form id="tera-form" style="margin-top: 10px;">
                        <input type="text" id="t-name" class="tera-form-input" placeholder="Nama Anda" required>
                        <input type="text" id="t-phone" class="tera-form-input" placeholder="Nomor WhatsApp" required>
                        <button type="submit" class="tera-btn">Mulai Chat</button>
                    </form>
                `;
                document.getElementById('tera-form').onsubmit = function(e) {
                    e.preventDefault();
                    var n = document.getElementById('t-name').value;
                    var p = document.getElementById('t-phone').value;
                    teraBody.innerHTML = '<div style="text-align:center; color:#888;">Memulai sesi...</div>';
                    
                    fetch(`${baseUrl}/api/widget/start`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({user_id: userId, name: n, phone: p})
                    }).then(res=>res.json()).then(d=>{
                        sessionId = d.session_id;
                        localStorage.setItem('tera_session_id_' + userId, sessionId);
                        teraFooter.style.display = 'flex';
                        loadMessages();
                        setInterval(loadMessages, 3000); // Polling pesan tiap 3 detik
                    });
                };
            } else {
                teraFooter.style.display = 'flex';
                loadMessages();
                if(!window.pollInterval) window.pollInterval = setInterval(loadMessages, 3000);
            }
        }

        function loadMessages() {
            fetch(`${baseUrl}/api/widget/${sessionId}/messages`)
                .then(res => res.json())
                .then(msgs => {
                    teraBody.innerHTML = '';
                    msgs.forEach(m => {
                        var div = document.createElement('div');
                        div.className = 'tera-msg ' + m.sender_type;
                        div.innerText = m.message;
                        teraBody.appendChild(div);
                    });
                    teraBody.scrollTop = teraBody.scrollHeight;
                });
        }

        document.getElementById('tera-send').onclick = sendMsg;
        teraInput.onkeypress = function(e) { if(e.key === 'Enter') sendMsg(); };

        function sendMsg() {
            var txt = teraInput.value.trim();
            if(!txt) return;
            teraInput.value = '';
            
            // Tambah ke UI langsung biar gak delay
            var div = document.createElement('div');
            div.className = 'tera-msg customer';
            div.innerText = txt;
            teraBody.appendChild(div);
            teraBody.scrollTop = teraBody.scrollHeight;

            fetch(`${baseUrl}/api/widget/send`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({session_id: sessionId, message: txt})
            }).then(() => loadMessages());
        }
    }
})();