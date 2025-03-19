<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useSessionStorage from '../composables/useSessionStorage';

const props = defineProps({
    ws: Object,
});

const { status, data, send, open, close } = props.ws;
const sessionStorage = useSessionStorage();

const messageContent = ref('');
const usersTotalInRoom = ref(null);
const messages = ref([]);

const waitingMessageSend = ref(false);
const waitingExit = ref(false);

const router = useRouter();
const route = useRoute();

if (! sessionStorage.value.token) {
    router.replace({ name: 'login' });
}

const callbacks = {
    joinRoom() {
        send(JSON.stringify({
            type: 'room.join',
            data: {
                user_id: sessionStorage.value.userId,
                room_id: route.params.id,
            },
        }));
    },

    exitRoom() {
        send(JSON.stringify({
            type: 'room.exit',
            data: {
                user_id: sessionStorage.value.userId,
                room_id: route.params.id,
            },
        }));

        waitingExit.value = true;
    },

    fetchAllMessages() {
        send(JSON.stringify({
            type: 'room.message.fetch_all',
            data: {
                room_id: route.params.id,
            },
        }));
    },

    sendMessage() {
        send(JSON.stringify({
            type: 'room.message.send',
            data: {
                user_id: sessionStorage.value.userId,
                room_id: route.params.id,
                content: messageContent.value.trim(),
            }
        }));
        waitingMessageSend.value = true;
        messageContent.value = '';
    }
};

onMounted(() => {
    callbacks.joinRoom();
    callbacks.fetchAllMessages();
});

watch(data, () => {
    const parsedData = JSON.parse(data.value);

    switch (parsedData.type) {
        case 'room.exit.success': {
            router.replace({ name: 'home' });
            break;
        }
        case 'room.users_total': {
            usersTotalInRoom.value = parsedData.data.users_total[route.params.id];
            break;
        }
        case 'room.message.fetch_all.success': {
            messages.value = parsedData.data.messages;
            break;
        }
        case `room_${route.params.id}_room.message.send`: {
            messages.value = parsedData.data.messages;
            waitingMessageSend.value = false;
            break;
        }
    }
});
</script>

<template>
    <button
        :disabled="waitingExit"
        @click="callbacks.exitRoom"
    >
        Exit room
    </button>

    <hr>
    <h1>
        Room {{ $route.params.id }}
        <span v-if="usersTotalInRoom">({{ usersTotalInRoom }})</span>
    </h1>
    <hr>
    <form @submit.prevent="callbacks.sendMessage">
        <label>Message</label>
        <textarea v-model="messageContent" rows="6"></textarea>
        <button
            :disabled="! messageContent || waitingMessageSend"
            type="submit"
        >
            Send message
        </button>
    </form>
    <ul>
        <li v-for="message in messages" :key="message.message_id">
            <strong>{{ message.username }}</strong>
            <p>
                {{ message.content }}
            </p>
            <hr>
        </li>
    </ul>
</template>