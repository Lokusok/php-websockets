<script setup>
import { useStorage } from '@vueuse/core';

const sessionStorage = useStorage('session', { username: null, token: null });

console.log(sessionStorage.value);
</script>

<template>
    <h1>Chat</h1>
</template>