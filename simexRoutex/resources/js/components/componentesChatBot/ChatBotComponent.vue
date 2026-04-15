<template>
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Botón Flotante -->
        <button @click="toggleChat"
            class="bg-orange-500 hover:bg-orange-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-95">
            <span v-if="!isOpen" class="text-2xl">💬</span>
            <span v-else class="text-xl">✕</span>
        </button>

        <!-- Ventana de Chat -->
        <div v-if="isOpen"
            class="absolute bottom-20 right-0 w-80 h-[450px] bg-white rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden animate-in slide-in-from-bottom-5">

            <!-- Cabecera -->
            <div class="p-4 bg-slate-800 text-white flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-widest">Simex Assistant</h3>
                    <p class="text-[9px] text-green-400 animate-pulse">● Online</p>
                </div>
            </div>

            <!-- Cuerpo de Mensajes -->
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3" ref="scrollBox">
                <div v-for="(msg, index) in messages" :key="index"
                    :class="msg.role === 'user' ? 'justify-end' : 'justify-start'" class="flex">
                    <div :class="msg.role === 'user' ? 'bg-orange-500 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-gray-200 rounded-tl-none'"
                        class="max-w-[85%] p-3 rounded-2xl text-[11px] shadow-sm">
                        {{ msg.text }}
                    </div>
                </div>
                <div v-if="isLoading" class="text-[10px] text-gray-400 italic">Simex está escribiendo...</div>
            </div>

            <!-- Input -->
            <div class="p-4 bg-white border-t flex gap-2">
                <input v-model="userInput" @keyup.enter="sendMessage" type="text" placeholder="¿En qué puedo ayudarte?"
                    class="flex-1 bg-gray-100 rounded-xl px-4 py-2 text-xs outline-none focus:ring-1 focus:ring-orange-400">
                <button @click="sendMessage" class="bg-slate-800 text-white px-3 py-2 rounded-xl text-xs hover:bg-slate-700">
                    ➤
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, nextTick } from 'vue';
    import axios from 'axios';

    const isOpen = ref(false);
    const userInput = ref('');
    const isLoading = ref(false);
    const scrollBox = ref(null);
    const messages = ref([
        { role: 'bot', text: '¡Hola! Soy tu asistente de Simex. ¿Quieres consultar el estado de una oferta?' }
    ]);

    const toggleChat = () => isOpen.value = !isOpen.value;

    const scrollToBottom = async () => {
        await nextTick();
        if (scrollBox.value) {
            scrollBox.value.scrollTop = scrollBox.value.scrollHeight;
        }
    };

    const sendMessage = async () => {
        if (!userInput.value.trim() || isLoading.value) return;

        const text = userInput.value;
        messages.value.push({ role: 'user', text });
        userInput.value = '';
        isLoading.value = true;
        scrollToBottom();

        try {
            // RECUERDA: Cambia esta URL por tu Webhook de n8n (Production URL si está en Active)
            const response = await axios.post('http://localhost:5678/webhook/ba85909a-9bae-4ee9-b8dc-0bd6e099146f/chat', {
                sessionId: 2,
                chatInput: text
            });

            messages.value.push({ role: 'bot', text: response.data.output });
        } catch (error) {
            messages.value.push({ role: 'bot', text: 'Lo siento, hay un problema de conexión con mi cerebro artificial.' });
        } finally {
            isLoading.value = false;
            scrollToBottom();
        }
    };
</script>

<style lang="scss" scoped>

</style>
