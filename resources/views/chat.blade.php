<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>

    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f172a;
            color: #f8fafc;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app {
            width: 900px;
            max-width: 95%;
            height: 80vh;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
        }

        .sidebar {
            width: 260px;
            background: #111827;
            border-right: 1px solid #334155;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #334155;
            font-weight: bold;
            font-size: 16px;
        }

        .sidebar-header small {
            display: block;
            font-weight: normal;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .users-list {
            flex: 1;
            overflow-y: auto;
        }

        .user-item {
            padding: 14px 20px;
            cursor: pointer;
            border-bottom: 1px solid #1e293b;
            transition: background 0.2s;
        }

        .user-item:hover {
            background: #1e293b;
        }

        .user-item.active {
            background: #1e3a5f;
        }

        .user-item .name {
            font-weight: 500;
        }

        .user-item .email {
            font-size: 12px;
            color: #94a3b8;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 20px;
            background: #111827;
            border-bottom: 1px solid #334155;
            font-size: 18px;
            font-weight: bold;
        }

        #status {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .message {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 10px;
            max-width: 80%;
            clear: both;
        }

        .me {
            background: #2563eb;
            margin-left: auto;
            text-align: right;
        }

        .other {
            background: #334155;
            margin-right: auto;
            text-align: left;
        }

        .system {
            background: #065f46;
            margin: 0 auto 10px;
            text-align: center;
            font-size: 13px;
        }

        .meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .footer {
            display: flex;
            gap: 10px;
            padding: 15px;
            border-top: 1px solid #334155;
            background: #111827;
        }

        .footer input {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #334155;
            color: white;
            outline: none;
        }

        .footer button {
            border: none;
            padding: 14px 25px;
            border-radius: 10px;
            background: #3b82f6;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .footer button:hover {
            opacity: .9;
        }

        .no-selection {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="sidebar">
            <div class="sidebar-header">
                Users
                <small id="connectionStatus">Connecting...</small>
            </div>
            <div class="users-list" id="usersList"></div>
        </div>

        <div class="chat-area">
            <div class="chat-header">
                <span id="chatHeader">Select a user to chat</span>
                <div id="status"></div>
            </div>

            <div id="messages">
                <div class="no-selection">Select a user from the sidebar to start chatting</div>
            </div>

            <div class="footer">
                <input
                    type="text"
                    id="message"
                    placeholder="Type a message..."
                    disabled>
                <button id="sendBtn" onclick="sendMessage()" disabled>
                    Send
                </button>
            </div>
        </div>
    </div>

    <script>
        const userData = JSON.parse(localStorage.getItem('user_data'));

        if (!userData) {
            window.location.assign('/login-page');
        }

        const api = axios.create({
            baseURL: '/api',
            headers: {
                Authorization: `Bearer ${userData.token}`,
                Accept: 'application/json',
            },
        });

        let selectedUserId = null;

        const socket = io('http://localhost:6001', {
            transports: ['websocket'],
        });

        const messagesEl = document.getElementById('messages');
        const statusEl = document.getElementById('status');
        const connectionStatus = document.getElementById('connectionStatus');
        const usersList = document.getElementById('usersList');
        const chatHeader = document.getElementById('chatHeader');
        const messageInput = document.getElementById('message');
        const sendBtn = document.getElementById('sendBtn');

        // ---------- Socket ----------

        socket.on('connect', () => {
            connectionStatus.textContent = '🟢 Connected';
            socket.emit('join-room', 'user_' + userData.id);
        });

        socket.on('disconnect', () => {
            connectionStatus.textContent = '🔴 Disconnected';
        });

        socket.on('connect_error', (err) => {
            connectionStatus.textContent = '🔴 Connection error';
            console.error('Socket connection error:', err.message);
        });

        socket.on('message', (data) => {
            const isMe = data.sender_id === userData.id;
            appendMessage(
                data.sender + ': ' + data.message,
                isMe ? 'me' : 'other',
                data.time || 'just now'
            );
        });

        // ---------- Load Users ----------

        async function loadUsers() {
            try {
                const res = await api.get('/users');
                const users = Array.isArray(res.data) ? res.data : (res.data?.data || []);

                usersList.innerHTML = '';
                let first = null;
                users.forEach((user) => {
                    if (user.id === userData.id) return;
                    if (!first) first = user;
                    const div = document.createElement('div');
                    div.className = 'user-item';
                    div.dataset.id = user.id;
                    div.innerHTML = `
                        <div class="name">${user.name}</div>
                        <div class="email">${user.email}</div>
                    `;
                    div.addEventListener('click', () => selectUser(user));
                    usersList.appendChild(div);
                });
                if (first) selectUser(first);
            } catch (err) {
                console.error('Failed to load users', err);
            }
        }

        // ---------- Select User ----------

        async function selectUser(user) {
            selectedUserId = user.id;
            chatHeader.textContent = 'Chat with ' + user.name;
            messageInput.disabled = false;
            sendBtn.disabled = false;

            document.querySelectorAll('.user-item').forEach((el) => {
                el.classList.toggle('active', parseInt(el.dataset.id) === user.id);
            });

     

            messagesEl.innerHTML = '';
            appendMessage('Chat with ' + user.name, 'system');

            try {
                const res = await api.get('/message/' + user.id);
                const msgs = res.data?.messages || res.data?.data || [];

                (Array.isArray(msgs) ? msgs : []).forEach((msg) => {
                    const isMe = msg.user_id === userData.id;
                    appendMessage(
                        (isMe ? 'Me' : msg.user?.name || 'Unknown') + ': ' + msg.body,
                        isMe ? 'me' : 'other',
                        msg.created_at
                    );
                });

                messagesEl.scrollTop = messagesEl.scrollHeight;
            } catch (err) {
                console.error('Failed to load messages', err);
            }
        }

        // ---------- Send Message ----------

        async function sendMessage() {
            const input = messageInput;
            const text = input.value.trim();

            if (!text || !selectedUserId) return;

            input.value = '';

            appendMessage('Me: ' + text, 'me');

            try {
                const res = await api.post('/message/' + selectedUserId, { body: text });
                const msg = res.data?.message || res.data?.data;
                socket.emit('message', {
                    receiver:  selectedUserId,
                    sender: userData.name,
                    sender_id: userData.id,
                    message: msg?.body || text,
                    time: msg?.created_at || 'just now',
                });
            } catch (err) {
                console.error('Failed to send message', err);
            }
        }

        // ---------- Append Message ----------

        function appendMessage(text, type = 'other', time = null) {
            const noSelection = messagesEl.querySelector('.no-selection');
            if (noSelection) noSelection.remove();

            const div = document.createElement('div');
            div.classList.add('message');

            if (type === 'me') {
                div.classList.add('me');
            } else if (type === 'other') {
                div.classList.add('other');
            } else {
                div.classList.add('system');
            }

            div.innerHTML = text;

            if (time) {
                const meta = document.createElement('div');
                meta.className = 'meta';
                meta.textContent = time;
                div.appendChild(meta);
            }

            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // ---------- Enter key ----------

        messageInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') sendMessage();
        });

        // ---------- Init ----------

        loadUsers();
    </script>

</body>

</html>
