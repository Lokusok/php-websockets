<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useWebSocket } from '@vueuse/core';

import wsConfig from '../config/ws';
import useSessionStorage from '../composables/useSessionStorage';

const { status, data, send, open, close } = useWebSocket(wsConfig.url);
const sessionStorage = useSessionStorage();

const usersTotalInRoom = ref(null);
const waitingExit = ref(false);

const router = useRouter();
const route = useRoute();

if (! sessionStorage.value.token) {
    router.replace({ name: 'login' });
}

const callbacks = {
    joinRoom() {
        console.log('Join to room: ', {
                user_id: sessionStorage.value.userId,
                room_id: route.params.id,
            });

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
};

onMounted(() => {
    callbacks.joinRoom();
});

watch(data, () => {
    const parsedData = JSON.parse(data.value);

    console.log(parsedData);

    switch (parsedData.type) {
        case 'room.exit.success': {
            setTimeout(() => {
                router.replace({ name: 'home' });
            }, 500);
            break;
        }
        case 'room.users_total': {
            usersTotalInRoom.value = parsedData.data.users_total[route.params.id];
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
    <form>
        <label>Message</label>
        <textarea rows="6"></textarea>
        <button>Send message</button>
    </form>
</template>