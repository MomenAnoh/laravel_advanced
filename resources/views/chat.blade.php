 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 20px auto; display: flex; gap: 20px; }
        .sidebar { width: 25%; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .chat-window { width: 75%; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; height: 600px; }
        #messages { flex: 1; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        input, button { padding: 10px; margin: 5px 0; }
        button { cursor: pointer; background: #3b82f6; color: white; border: none; border-radius: 5px; }
        li { cursor: pointer; padding: 5px; border-radius: 5px; }
        li:hover { background: #f0f0f0; }
        .msg-left { background: #eee; text-align: left; padding: 5px; border-radius: 5px; margin-bottom: 5px; }
        .msg-right { background: #cce5ff; text-align: right; padding: 5px; border-radius: 5px; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h3>Users</h3>
        <ul id="users"></ul>
    </div>

    <div class="chat-window">
        <h3 id="chatWith">Select a user to chat</h3>
        <div id="messages"></div>
        <form id="messageForm" style="display:flex; gap:10px;">
            <input type="text" id="messageInput" placeholder="Type your message..." style="flex:1;"/>
            <button type="submit">Send</button>
        </form>
    </div>
</div>

<script>
    // جلب بيانات المستخدم من localStorage
    const userData = JSON.parse(localStorage.getItem('user_data'));
    if(!userData) {
        alert('User not logged in!');
        window.location.href = '/auth';
    }

    const token = userData.token;
    let selectedUserId = null;

    // إعداد Axios مع Authorization header
    const axiosInstance = axios.create({
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });


    axiosInstance.get("http://127.0.0.1:8000/api/users")
        .then(res => {
            const ul = document.getElementById('users');
            res.data.data.forEach(user => {
                if(user.id !== userData.id) {  
                    const li = document.createElement('li');
                    li.textContent = user.name;
                    li.addEventListener('click', () => selectUser(user));
                    ul.appendChild(li);
                }
            });
        });

    // تحميل رسائل مع يوزر محدد
    function selectUser(user) {
        selectedUserId = user.id;
        document.getElementById('chatWith').textContent = `Chat with ${user.name}`;
        document.getElementById('messages').innerHTML = '';

        axiosInstance.get(`http://127.0.0.1:8000/api/message/${user.id}`)
            .then(res => {
                res.data.messages.forEach(msg => addMessage(msg));
            });
    }
    document.getElementById('messageForm').addEventListener('submit', function(e){
        e.preventDefault();
        if(!selectedUserId) return alert('Select a user first');
        const body = document.getElementById('messageInput').value;
        if(!body.trim()) return;

        axiosInstance.post(`http://127.0.0.1:8000/api/message/${selectedUserId}`, { body })
            .then(res => {
                document.getElementById('messageInput').value = '';
                // هذا السطر يضمن ظهور الرسالة للمرسل على الفور
                addMessage(res.data.message);
            });
    });

    // دالة إضافة رسالة
    function addMessage(msg) {
        // backend لازم يبعت user مع الرسالة
        const senderId = msg.user_id || msg.user?.id;
        const senderName = msg.user?.name || (senderId === userData.id ? userData.name : "User");

        const isCurrentUser = senderId === userData.id;
        const div = document.createElement('div');
        div.className = isCurrentUser ? 'msg-right' : 'msg-left';
        div.innerHTML = `<strong>${senderName}</strong>: ${msg.body} <br><small>${msg.created_at}</small>`;

        const container = document.getElementById('messages');
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    // 👇 الاشتراك مرة واحدة في قناة خاصة بيك
    window.Echo.private(`Private.chat.${userData.id}`)
        .listen('MessageSent', (e) => {
            // التحقق من أن الرسالة تخص الشات المفتوح حاليًا
            // سواء كانت مرسلة من المستخدم الآخر أو مرسلة منك
            if (e.message.user_id === selectedUserId || e.message.receiver_id === selectedUserId) {
                addMessage(e.message);
            }
        });
</script>

</body>
</html> 


