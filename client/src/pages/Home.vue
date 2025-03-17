<script setup>
import { ref, watch, onMounted } from 'vue';
import { useStorage, useWebSocket } from '@vueuse/core';

const { status, data, send, open, close } = useWebSocket('ws://127.0.0.1:9502');
const sessionStorage = useStorage('session', { userId: null, username: null, token: null });

const roomTitle = ref('');
const rooms = ref([]);

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

    if (parsedData.type === 'room.fetch_all.success') {
        rooms.value = parsedData.data;
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

    <ul>
        <li 
            v-for="room in rooms"
            :key="room.id"
        >
            {{ room.title }}
            <br>
            <router-link>Join room</router-link>
        </li>
    </ul>

</template>