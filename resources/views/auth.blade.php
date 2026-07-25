<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        button { cursor: pointer; background: #3b82f6; color: white; border: none; border-radius: 5px; }
        .toggle { text-align: center; margin-top: 10px; cursor: pointer; color: #3b82f6; }
    </style>
</head>
<body>
<div class="container">
    <h2 id="formTitle">Login</h2>

    <div id="formContainer">
        <input type="text" id="name" placeholder="Name (for register)" style="display:none;" />
        <input type="email" id="email" placeholder="Email" />
        <input type="password" id="password" placeholder="Password" />
        <button id="submitBtn">Login</button>
    </div>

    <div class="toggle" id="toggleForm">Switch to Register</div>
</div>

<script>
    let isLogin = true;
    const toggleForm = document.getElementById('toggleForm');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const nameInput = document.getElementById('name');

    toggleForm.addEventListener('click', () => {
        isLogin = !isLogin;
        formTitle.textContent = isLogin ? 'Login' : 'Register';
        submitBtn.textContent = isLogin ? 'Login' : 'Register';
        nameInput.style.display = isLogin ? 'none' : 'block';
    });

    submitBtn.addEventListener('click', async () => {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const name = document.getElementById('name').value;

        try {
            const url = isLogin ? '/api/login' : '/api/register';
            const payload = isLogin ? { email, password } : { name, email, password };

            const res = await axios.post(url, payload);

            // دمج بيانات المستخدم مع التوكن وتخزينهم في localStorage
            const userData = { ...res.data.data, token: res.data.token };
            localStorage.setItem('user_data', JSON.stringify(userData));

            // تحويل المستخدم مباشرة لصفحة الشات
            window.location.assign('/chat');
        } catch(err) {
            alert(err.response?.data?.message || 'Error occurred');
            console.error(err);
        }
    });
</script>
</body>
</html>
