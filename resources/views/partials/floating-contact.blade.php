{{-- resources/views/partials/floating-contact.blade.php --}}

<div class="floating-contact-wrapper">

    {{-- 1. DANH SÁCH CÁC KÊNH LIÊN HỆ --}}
    <div class="contact-options" id="contactOptions">
        {{-- Nút Zalo --}}
        <a href="https://zalo.me/0919795426" target="_blank" class="contact-item zalo" title="Chat Zalo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Icon_of_Zalo.svg/1200px-Icon_of_Zalo.svg.png" alt="Zalo">
        </a>

        {{-- Nút Messenger --}}
        <a href="https://m.me/your.facebook.id" target="_blank" class="contact-item messenger" title="Chat Facebook">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Facebook_Messenger_logo_2020.svg/2048px-Facebook_Messenger_logo_2020.svg.png" alt="Messenger">
        </a>

        {{-- Nút Gọi điện --}}
        <a href="tel:0919795426" class="contact-item phone" title="Gọi ngay">
            <i class="fa fa-phone-alt"></i>
        </a>

        {{-- Nút Mở Chatbot --}}
        <button type="button" class="contact-item chatbot-trigger" onclick="toggleChatWindow()" title="Tư vấn thuốc">
            <i class="fa fa-robot"></i>
        </button>
    </div>

    {{-- 2. NÚT CHÍNH (Toggle) --}}
    <button class="main-floating-btn" onclick="toggleContactOptions()">
        <i class="fa fa-comments text-white fs-4 icon-chat"></i>
        <i class="fa fa-times text-white fs-4 icon-close d-none"></i>
    </button>

    {{-- 3. CỬA SỔ CHATBOT (Bỏ d-none, để SCSS lo phần ẩn hiện) --}}
    <div class="chatbot-window shadow" id="chatbotWindow">
        <div class="chatbot-header d-flex justify-content-between align-items-center bg-primary text-white p-3">
            <div class="d-flex align-items-center">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                    <i class="fa fa-user-md fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Dược sĩ ảo Dola</h6>
                    <small class="text-white-50" style="font-size: 12px;">Hỗ trợ 24/7</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" onclick="toggleChatWindow()"></button>
        </div>

        <div class="chatbot-body p-3" id="chatContent">
            {{-- Tin nhắn chào mừng --}}
            <div class="chat-msg bot mb-3">
                <div class="msg-text bg-white p-2 rounded shadow-sm d-inline-block text-dark">
                    Xin chào! Tôi là trợ lý ảo của Dola Pharmacy. <br>
                    Bạn cần tìm thuốc, hỏi giá hay tư vấn bệnh gì ạ?
                </div>
            </div>
        </div>

        {{-- Gợi ý câu hỏi --}}
        <div class="chat-suggestions p-2 bg-light border-top d-flex gap-2 overflow-auto">
            <button class="btn btn-outline-primary btn-sm rounded-pill text-nowrap" onclick="sendSuggestion('Địa chỉ nhà thuốc?')">Địa chỉ?</button>
            <button class="btn btn-outline-primary btn-sm rounded-pill text-nowrap" onclick="sendSuggestion('Phí ship bao nhiêu?')">Phí ship?</button>
            <button class="btn btn-outline-primary btn-sm rounded-pill text-nowrap" onclick="sendSuggestion('Tư vấn thuốc cảm')">Thuốc cảm</button>
        </div>

        <div class="chatbot-footer p-2 bg-white border-top">
            <div class="input-group">
                <input type="text" class="form-control border-0 bg-light" placeholder="Nhập câu hỏi..." id="chatInput" onkeypress="handleEnter(event)">
                <button class="btn btn-primary" onclick="sendMessage()">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    // 1. Ẩn/Hiện Menu nút nhỏ
    function toggleContactOptions() {
        const options = document.getElementById('contactOptions');
        const iconChat = document.querySelector('.icon-chat');
        const iconClose = document.querySelector('.icon-close');

        // Toggle class 'show' cho menu con (đã định nghĩa trong app.scss)
        options.classList.toggle('show');

        // Đảo icon Chat/Close
        if (options.classList.contains('show')) {
            iconChat.classList.add('d-none');
            iconClose.classList.remove('d-none');
        } else {
            iconChat.classList.remove('d-none');
            iconClose.classList.add('d-none');
        }
    }

    // 2. Ẩn/Hiện Chatbot (Dùng class active để kích hoạt transform: scale(1) trong SCSS)
    function toggleChatWindow() {
        const win = document.getElementById('chatbotWindow');
        const options = document.getElementById('contactOptions');

        // Toggle class 'active'
        win.classList.toggle('active');

        if (win.classList.contains('active')) {
            // Khi mở chat thì đóng menu nút nhỏ lại cho gọn
            options.classList.remove('show');
            document.querySelector('.icon-chat').classList.remove('d-none');
            document.querySelector('.icon-close').classList.add('d-none');

            // Focus vào ô nhập liệu
            setTimeout(() => document.getElementById('chatInput').focus(), 300);
        }
    }

    // 3. Gửi tin nhắn
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if(!message) return;

        // Hiện tin nhắn người dùng
        appendMessage(message, 'user');
        input.value = '';

        // Lấy CSRF Token
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        // Gửi AJAX lên Server
        fetch('/chatbot/reply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            appendMessage(data.reply, 'bot');
        })
        .catch(error => {
            console.error('Lỗi:', error);
            appendMessage("Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau.", 'bot');
        });
    }

    function sendSuggestion(text) {
        document.getElementById('chatInput').value = text;
        sendMessage();
    }

    function appendMessage(text, sender) {
        const chatBody = document.getElementById('chatContent');
        const div = document.createElement('div');

        // Dùng class 'chat-msg' đã định nghĩa trong SCSS
        div.className = `chat-msg ${sender} mb-3`;

        // Xử lý xuống dòng
        const formattedText = text.replace(/\n/g, '<br>');

        const content = `
            <div class="msg-text shadow-sm">
                ${formattedText}
            </div>
        `;
        div.innerHTML = content;

        // Thêm css flex để căn trái/phải vì SCSS chỉ chỉnh màu chứ chưa căn lề cha
        if(sender === 'user') {
            div.style.marginLeft = 'auto'; // Đẩy sang phải
            div.style.textAlign = 'right';
        }

        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function handleEnter(e) {
        if(e.key === 'Enter') sendMessage();
    }
</script>
