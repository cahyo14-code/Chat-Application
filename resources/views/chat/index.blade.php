<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat App</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
    body {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .navbar {
        padding: 10px 16px;
        background: #fff;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-wrap {
        display: flex;
        flex: 1;
        overflow: hidden;
    }
    .sidebar {
        width: 240px;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        background: #fff;
    }
    .sidebar a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        text-decoration: none;
        color: #222;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }
    .sidebar a:hover,
    .sidebar a.active {
        background: #f5f5f5;
    }
    .sidebar .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ccc;
        flex-shrink: 0;
    }
    .sidebar .dot.online {
        background: green;
    }
    .sidebar .section {
        padding: 8px 14px;
        font-size: 11px;
        color: #999;
        background: #fafafa;
        border-bottom: 1px solid #eee;
    }
    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .chat-header {
        padding: 10px 16px;
        background: #fff;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
        font-weight: bold;
    }
    .messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: #f9f9f9;
    }
    .msg {
        display: flex;
        flex-direction: column;
        max-width: 60%;
    }
    .msg.mine {
        align-self: flex-end;
        align-items: flex-end;
    }
    .msg.others {
        align-self: flex-start;
        align-items: flex-start;
    }
    .msg .name {
        font-size: 11px;
        color: #999;
        margin-bottom: 2px;
    }
    .msg .bubble {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
    }
    .msg.mine .bubble {
        background: #222;
        color: #fff;
    }
    .msg.others .bubble {
        background: #fff;
        border: 1px solid #ddd;
        color: #222;
    }
    .msg .time {
        font-size: 10px;
        color: #bbb;
        margin-top: 2px;
    }
    .input-area {
        display: flex;
        gap: 8px;
        padding: 10px 16px;
        background: #fff;
        border-top: 1px solid #ddd;
    }
    .input-area input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
    }
    .input-area button {
        padding: 8px 16px;
        background: #222;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
    }
    .empty {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #bbb;
        font-size: 13px;
    }
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
    }
    .modal.show {
        display: flex;
    }
    .modal-box {
        background: #fff;
        padding: 24px;
        width: 320px;
        border-radius: 8px;
    }
    .modal-box input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
        font-size: 13px;
    }
    .modal-box label {
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 0;
    }
    .modal-box .actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 10px;
    }
    .modal-box .actions button {
        padding: 7px 14px;
        font-size: 13px;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-cancel {
        background: #fff;
        border: 1px solid #ccc;
    }
    .btn-create {
        background: #222;
        color: #fff;
        border: none;
    }
    form.inline {
        display: inline;
    }
    button.logout {
        background: none;
        border: 1px solid #ccc;
        padding: 4px 10px;
        cursor: pointer;
        font-size: 12px;
        border-radius: 4px;
    }
    </style>
</head>
<body>

<div class="navbar">
    <b>Chat Application</b>
    <span style="font-size:13px">{{ Auth::user()->name }} &nbsp;
        <form class="inline" method="POST" action="{{ route('logout') }}">
            @csrf <button class="logout">Keluar</button>
        </form>
    </span>
</div>

<div class="chat-wrap">
    <div class="sidebar">
        <div class="section">PENGGUNA</div>
        @foreach($users as $user)
            <a href="{{ route('chat.private', $user->id) }}">
                <div class="dot" id="dot-{{ $user->id }}"></div>
                {{ $user->name }}
            </a>
        @endforeach

            <div class="section" style="display:flex;justify-content:space-between;align-items:center">
                PERCAKAPAN
                <span onclick="document.getElementById('groupModal').classList.add('show')" style="cursor:pointer;font-size:12px;color:#555">+ Grup</span>
            </div>
            @foreach($conversations as $conv)
                <a href="{{ route('chat.show', $conv->id) }}" class="{{ isset($conversation) && $conversation->id == $conv->id ? 'active' : '' }}">
                    {{ $conv->type == 'group' ? '# '.$conv->name : $conv->users->where('id','!=',Auth::id())->first()->name ?? 'Unknown' }}
                </a>
            @endforeach
    </div>

    <div class="chat-area">
        @isset($conversation)
            <div class="chat-header">
                {{ $conversation->type == 'group' ? '# '.$conversation->name : $conversation->users->where('id','!=',Auth::id())->first()->name ?? 'Unknown' }}
            </div>
            <div class="messages" id="messages-box">
                @foreach($messages as $msg)
                    <div class="msg {{ $msg->user_id == Auth::id() ? 'mine' : 'others' }}">
                        @if($msg->user_id != Auth::id())<div class="name">{{ $msg->user->name }}</div>@endif
                        <div class="bubble">{{ $msg->body }}</div>
                        <div class="time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                @endforeach
            </div>
            <div class="input-area">
                <input type="text" id="msg-input" placeholder="Tulis pesan..." autocomplete="off">
                <button onclick="sendMessage()">Kirim</button>
            </div>
        @else
            <div class="empty">Pilih pengguna untuk mulai chat 💬</div>
        @endisset
    </div>
</div>

<div class="modal" id="groupModal">
    <div class="modal-box">
        <b style="font-size:15px">Buat Grup Baru</b>
        <form method="POST" action="{{ route('chat.group.create') }}" style="margin-top:14px">
            @csrf
            <input type="text" name="name" placeholder="Nama grup" required>
            @foreach($users as $user)
                <label><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"> {{ $user->name }}</label>
            @endforeach
            <div class="actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('groupModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-create">Buat</button>
            </div>
        </form>
    </div>
</div>

@vite(['resources/js/app.js'])

<script>
    var conversationId = {{ isset($conversation) ? $conversation->id : 'null' }};
    var authUserId = {{ Auth::id() }};
    var sendUrl = conversationId ? '/chat/' + conversationId + '/send' : null;
    var token = document.querySelector('meta[name="csrf-token"]').content;

    var box = document.getElementById('messages-box');
    if (box) box.scrollTop = box.scrollHeight;

    function appendMessage(body, name, time, mine) {
        var box = document.getElementById('messages-box');
        if (!box) return;
        var div = document.createElement('div');
        div.className = 'msg ' + (mine ? 'mine' : 'others');
        if (!mine) {
            var nameDiv = document.createElement('div');
            nameDiv.className = 'name';
            nameDiv.textContent = name;
            div.appendChild(nameDiv);
        }
        var bubble = document.createElement('div');
        bubble.className = 'bubble';
        bubble.textContent = body;
        var timeDiv = document.createElement('div');
        timeDiv.className = 'time';
        timeDiv.textContent = time;
        div.appendChild(bubble);
        div.appendChild(timeDiv);
        box.appendChild(div);
        box.scrollTop = box.scrollHeight;
    }

    function sendMessage() {
        var input = document.getElementById('msg-input');
        var body = input.value.trim();
        if (!body || !sendUrl) return;
        fetch(sendUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ body: body })
        }).then(function(r) {
            return r.json();
        }).then(function(data) {
            if (data.status === 'success') {
                appendMessage(data.message.body, data.message.user_name, data.message.created_at, true);
                input.value = '';
            }
        });
    }

    var msgInput = document.getElementById('msg-input');
    if (msgInput) {
        msgInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
    }

    window.addEventListener('load', function() {
        if (!window.Echo) return;

        setTimeout(function() {

            // Online/Offline tracking + group created listener
            window.Echo.join('online')
                .here(function(users) {
                    users.forEach(function(user) {
                        var dot = document.getElementById('dot-' + user.id);
                        if (dot) dot.classList.add('online');
                    });
                })
                .joining(function(user) {
                    var dot = document.getElementById('dot-' + user.id);
                    if (dot) dot.classList.add('online');
                })
                .leaving(function(user) {
                    var dot = document.getElementById('dot-' + user.id);
                    if (dot) dot.classList.remove('online');
                })
                .listen('.group.created', function(e) {
                    window.location.reload();
                });

            // Real-time pesan
            if (conversationId) {
                window.Echo.join('conversation.' + conversationId)
                    .listen('.message.sent', function(e) {
                        if (e.user_id !== authUserId) {
                            appendMessage(e.body, e.user_name, e.created_at, false);
                        }
                    });
            }

        }, 1000);
    });
</script>
</body>
</html>