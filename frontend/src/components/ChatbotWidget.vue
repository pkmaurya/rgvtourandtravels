<template>
  <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <!-- Chat Window -->
    <transition name="fade">
      <div v-if="isOpen" class="bg-white w-80 sm:w-96 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden mb-4 flex flex-col h-[500px]">
        <!-- Header -->
        <div class="bg-indigo-600 p-4 text-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-2">
                <div class="bg-white/20 p-1.5 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <div>
                     <h3 class="font-bold">Tour Assistant</h3>
                     <p class="text-xs text-indigo-100">Usually replies instantly</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="endChat" title="End Chat" class="text-indigo-100 hover:text-white transition-colors p-1 rounded hover:bg-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50 space-y-4 scrollbar-thin" ref="messagesContainer">
            <div v-for="(msg, index) in messages" :key="index" :class="['flex', msg.isUser ? 'justify-end' : 'justify-start']">
                <div :class="['max-w-[85%] rounded-2xl px-4 py-2 text-sm shadow-sm', msg.isUser ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-800 rounded-bl-none border border-gray-100']">
                    {{ msg.text }}
                    <!-- Suggestions in Message -->
                    <div v-if="msg.suggestions && msg.suggestions.length > 0" class="mt-3 space-y-1">
                        <button v-for="q in msg.suggestions" :key="q.id" @click="askQuestion(q)" class="block w-full text-left text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 p-2 rounded transition-colors border border-indigo-100">
                            {{ q.question }}
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Typing Indicator -->
            <div v-if="isTyping" class="flex justify-start">
                 <div class="bg-white border border-gray-100 rounded-2xl rounded-bl-none px-4 py-2 shadow-sm flex space-x-1 items-center">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        </div>

        <!-- Input / Options Area -->
        <div class="p-4 bg-white border-t border-gray-100 shrink-0">
             <!-- Quick Categories (only if no active conversation or explicitly requested) -->
             <div v-if="showCategories" class="flex gap-2 overflow-x-auto pb-2 mb-2 custom-scrollbar">
                 <button @click="selectCategory('tour')" class="whitespace-nowrap bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium py-1.5 px-3 rounded-full border border-indigo-100 transition-colors">
                     🏝️ Tours
                 </button>
                 <button @click="selectCategory('support')" class="whitespace-nowrap bg-green-50 hover:bg-green-100 text-green-700 text-xs font-medium py-1.5 px-3 rounded-full border border-green-100 transition-colors">
                     📞 Support
                 </button>
                 <button @click="selectCategory('general')" class="whitespace-nowrap bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium py-1.5 px-3 rounded-full border border-gray-200 transition-colors">
                     ❓ General
                 </button>
             </div>

             <!-- Input Form -->
             <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                 <input 
                    v-model="userMessage" 
                    type="text" 
                    placeholder="Type your message..." 
                    class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                 >
                 <button type="submit" :disabled="!userMessage.trim() || isTyping" class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-full p-2 transition-colors shadow-md">
                     <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                 </button>
             </form>
        </div>
      </div>
    </transition>

    <!-- Toggle Button -->
    <button v-if="!isOpen" @click="toggleChat" class="group bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg transition-all hover:scale-105 duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-300">
       <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
       <!-- Notification Badge -->
      <span v-if="showBadge" class="absolute top-0 right-0 -mr-1 -mt-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-bounce">1</span>
    </button>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'
import api from '../services/api'

const isOpen = ref(false)
const showBadge = ref(true)
const messages = ref([])
const isTyping = ref(false)
const userMessage = ref('')
const allQuestions = ref([])
const messagesContainer = ref(null)
const showCategories = ref(true)

const INITIAL_MESSAGE = { text: "Hi! 👋 capturing memories? How can I help you today? You can select a topic below or type your question.", isUser: false }

const toggleChat = () => {
    isOpen.value = true
    showBadge.value = false
    if (messages.value.length === 0) {
        messages.value.push(INITIAL_MESSAGE)
    }
    scrollToBottom()
}

const endChat = () => {
    if(confirm("End current chat session?")) {
        isOpen.value = false
        setTimeout(() => {
            messages.value = []
            showCategories.value = true
        }, 300)
    }
}

const scrollToBottom = () => {
    nextTick(() => {
        if(messagesContainer.value) {
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
        }
    })
}

// Fetch all questions once on mount for client-side search
const fetchAllQuestions = async () => {
    // No longer needed to fetch all questions for client-side search
    // But we might want to fetch initial categories or suggestions if needed
}

const selectCategory = (cat) => {
    const categoryName = cat === 'tour' ? 'Tours' : cat === 'support' ? 'Support' : 'General'
    messages.value.push({ text: `I'm interested in ${categoryName}.`, isUser: true })
    scrollToBottom()
    
    isTyping.value = true
    showCategories.value = false

    // Send as a message to the agent to get relevant suggestions
    sendMessageToAgent(`Show me ${categoryName} questions`)
}

const sendMessage = () => {
    const text = userMessage.value.trim()
    if (!text) return

    messages.value.push({ text: text, isUser: true })
    userMessage.value = ''
    showCategories.value = false
    scrollToBottom()

    isTyping.value = true
    sendMessageToAgent(text)
}

const sendMessageToAgent = async (text) => {
    try {
        const response = await api.post('/chatbot/ask', { message: text })
        const data = response.data
        
        isTyping.value = false
        
        // Add Answer
        if (data.answer) {
             messages.value.push({ 
                text: data.answer, 
                isUser: false,
                suggestions: data.suggestions || []
            })
        }
        scrollToBottom()

    } catch (error) {
        console.error("Agent failed", error)
        isTyping.value = false
        messages.value.push({ 
            text: "I'm having trouble connecting to the server. Please try again later.", 
            isUser: false 
        })
        showCategories.value = true
        scrollToBottom()
    }
}

const askQuestion = (q) => {
    // Treat clicking a suggestion as sending a message
    messages.value.push({ text: q.question, isUser: true })
    scrollToBottom()
    isTyping.value = true
    sendMessageToAgent(q.question)
}

onMounted(() => {
    // fetchAllQuestions() // Not needed for server-side agent
})

watch(isOpen, (val) => {
    if(val) scrollToBottom()
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    height: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c7c7c7;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
