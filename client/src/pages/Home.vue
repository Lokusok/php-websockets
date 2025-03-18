<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import useSessionStorage from '../composables/useSessionStorage';

const props = defineProps({
    ws: Object,
});

const { status, data, send, open, close } = props.ws;
const sessionStorage = useSessionStorage();

const router = useRouter();

if (! sessionStorage.value.token) {
    router.replace({ name: 'login' });
}

const roomTitle = ref('');
const rooms = ref([]);
const error = ref('');
const waiting = ref(false);

const callbacks = {
    fetchAllRooms() {
        send(JSON.stringify({
            type: 'room.fetch_all',
            data: {
                user_id: sessionStorage.value.userId,
            },
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

    deleteRoom(roomId) {
        send(JSON.stringify({
            type: 'room.delete',
            data: {
                user_id: sessionStorage.value.userId,
                room_id: roomId
            },
        }));
        waiting.value = true;
    },
};

onMounted(() => {
    callbacks.fetchAllRooms();
});

watch(data, () => {
    const parsedData = JSON.parse(data.value);

    error.value = '';

    console.log(parsedData);

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
            rooms.value = parsedData.data.rooms;
            rooms.value = rooms.value.map((r) => {
                r.currentUserIn = parsedData.data.current_user_in[r.id];
                return r;
            });
            break;
        }
        case 'room.delete.success': {
            rooms.value = rooms.value.filter((r) => r.id !== parsedData.data.deleted_id);
            break;
        }
        case 'room.join.success': {
            const room = rooms.value.find((r) => r.id === parsedData.data.room_id);
            room.currentUserIn = true;
            break;
        }
        case 'room.exit.success': {
            const room = rooms.value.find((r) => r.id === parsedData.data.room_id);
            room.currentUserIn = false;
            break;
        }
        case 'room.users_total': {
            rooms.value = rooms.value.map((r) => {
                r.users_total = parsedData.data.users_total[r.id] ?? 0;
                return r;
            });
            break;
        }
    }

    waiting.value = false;
});
</script>

<template>
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
            <span>
                {{ room.title }}
            </span>
            <span v-if="room.users_total">
                &nbsp;({{ room.users_total }})
            </span>
            <router-link
                :to="{ name: 'room', params: { id: room.id } }"
            >
                Join room
            </router-link>
            <br>
            <br>
            <div style="display: flex; gap: 10px;">
                <button
                    v-if="room.user_id === sessionStorage.userId"
                    :disabled="waiting"
                    @click="callbacks.deleteRoom(room.id)"
                >
                    Delete
                </button>
    
                <button v-if="room.currentUserIn" @click="callbacks.exitRoom(room.id)">Exit room</button>
            </div>
            <hr>
        </li>
    </ul>

    <p v-else>Rooms not found...</p>
</template>