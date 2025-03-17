<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useWebSocket } from '@vueuse/core';

import wsConfig from '../config/ws';
import useSessionStorage from '../composables/useSessionStorage';

const { status, data, send, open, close } = useWebSocket(wsConfig.url);
const sessionStorage = useSessionStorage();

const router = useRouter();

if (! sessionStorage.value.token) {
    router.replace({ name: 'login' });
}

const roomTitle = ref('');
const rooms = ref([]);
const error = ref('');

const callbacks = {
    fetchAllRooms() {
        send(JSON.stringify({
            type: 'room.fetch_all',
        }));
    },

    createRoom() {
        send(JSON.stringify({
            type: 'room.create',
            data: {
                title: roomTitle.value.trim(),
                user_id: sessionStorage.value.userId,
            },
        }));

        roomTitle.value = '';
    },
};

onMounted(() => {
    callbacks.fetchAllRooms();
});

watch(data, () => {
    const parsedData = JSON.parse(data.value);

    error.value = '';

    switch (parsedData.type) {
        case 'room.create.success': {
            rooms.value.unshift(parsedData.data);
            break;
        }
        case 'room.create.error': {
            error.value = parsedData.data.message;
            break;
        }
        case 'room.fetch_all.success': {
            rooms.value = parsedData.data;
            break;
        }
    }
});
</script>

<template>
    <div>
        <h3>Login as: {{ sessionStorage.username }}</h3>
    </div>

    <hr>

    <div>
        <details>
            <summary>Want create new room?</summary>
            
            <p v-if="error" style="color: red">{{ error }}</p>

            <form @submit.prevent="callbacks.createRoom">
                <label>
                    Room title:
                    <br>
                    <input v-model="roomTitle" type="text">
                </label>

                <button :disabled="! roomTitle">Create room</button>
            </form>
        </details>
    </div>

    <hr>

    <h1>Rooms overview</h1>

    <hr>

    <ul v-if="rooms.length">
        <li 
            v-for="room in rooms"
            :key="room.id"
        >
            {{ room.title }}
            <br>
            <button v-if="room.user_id === sessionStorage.userId">Delete</button>
            <br>
            <router-link>Join room</router-link>
            <hr>
        </li>
    </ul>

    <p v-else>Rooms not found...</p>
</template>