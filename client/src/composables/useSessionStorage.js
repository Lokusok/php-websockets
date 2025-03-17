import { useStorage } from '@vueuse/core';

function useSessionStorage() {
    const sessionStorage = useStorage('session', { userId: null, username: null, token: null });

    return sessionStorage;
}

export default useSessionStorage;
