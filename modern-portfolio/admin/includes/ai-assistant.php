<!-- Madmin AI Assistant Widget -->
<div id="madmin-ai-assistant" class="madmin-ai-widget">
    <div class="ai-bubble" id="ai-chat-bubble">
        <div class="ai-header">
            <span>✦ Madmin AI</span>
            <button onclick="toggleAIChat()"><i class="fas fa-times"></i></button>
        </div>
        <div class="ai-messages" id="ai-message-container">
            <div class="msg bot">Hello Praveen! I'm your AI career agent. How can I help you today?</div>
        </div>
        <div class="ai-input">
            <input type="text" id="ai-query" placeholder="Type or use voice..." onkeypress="if(event.key === 'Enter') sendAIQuery()">
            <button onclick="startVoice()"><i class="fas fa-microphone"></i></button>
            <button onclick="sendAIQuery()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <button class="ai-toggle-btn" onclick="toggleAIChat()">
        <i class="fas fa-robot"></i>
    </button>
</div>

<style>
.madmin-ai-widget { position: fixed; bottom: 30px; right: 30px; z-index: 9999; }
.ai-toggle-btn { width: 60px; height: 60px; border-radius: 30px; background: #4770FF; color: #fff; border: none; font-size: 24px; box-shadow: 0 10px 30px rgba(71, 112, 255, 0.4); transition: 0.3s; }
.ai-toggle-btn:hover { transform: scale(1.1); }
.ai-bubble { position: absolute; bottom: 80px; right: 0; width: 350px; height: 450px; background: rgba(15, 15, 15, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; backdrop-filter: blur(20px); display: none; flex-direction: column; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
.ai-header { padding: 15px 20px; background: rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); font-weight: 700; }
.ai-header button { background: none; border: none; color: #888; }
.ai-messages { flex-grow: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; }
.msg { padding: 10px 15px; border-radius: 15px; font-size: 14px; max-width: 80%; }
.msg.bot { background: rgba(71, 112, 255, 0.1); border: 1px solid rgba(71, 112, 255, 0.2); align-self: flex-start; }
.msg.user { background: rgba(255,255,255,0.05); align-self: flex-end; }
.ai-input { padding: 15px; display: flex; gap: 10px; background: rgba(255,255,255,0.02); }
.ai-input input { flex-grow: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #fff; padding: 8px 12px; font-size: 14px; }
.ai-input button { background: none; border: none; color: #4770FF; font-size: 18px; }
</style>

<script>
function toggleAIChat() {
    const bubble = document.getElementById('ai-chat-bubble');
    bubble.style.display = bubble.style.display === 'flex' ? 'none' : 'flex';
}

function sendAIQuery() {
    const input = document.getElementById('ai-query');
    const query = input.value;
    if(!query) return;
    
    addMessage(query, 'user');
    input.value = '';
    
    // AI Response Logic
    fetch('api/ai_chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: query })
    })
    .then(res => res.json())
    .then(data => {
        if(data.error) {
            addMessage("Error: " + data.error, 'bot');
        } else {
            addMessage(data.response, 'bot');
        }
    })
    .catch(err => {
        console.error(err);
        addMessage("Sorry, I'm having trouble connecting to the AI server.", 'bot');
    });
}

function addMessage(text, type) {
    const container = document.getElementById('ai-message-container');
    const div = document.createElement('div');
    div.className = `msg ${type}`;
    div.innerText = text;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function startVoice() {
    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.onresult = (event) => {
            document.getElementById('ai-query').value = event.results[0][0].transcript;
            sendAIQuery();
        };
        recognition.start();
    } else {
        alert("Voice recognition not supported in this browser.");
    }
}
</script>
