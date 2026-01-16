import { writable, get } from 'svelte/store';
import type { User, AuthState } from '$lib/api/types/__index__';
import { getMe } from '$lib/api/user/me';

const auth = writable<AuthState>({
    user: null,
    loading: false,
    initialized: false
});

export const authStore = {
    subscribe: auth.subscribe,

    get user() {
        return get(auth).user;
    },
    setUser(user: User) {
        auth.update((s) => ({ ...s, user, initialized: true }));
    },
    clearUser() {
        auth.update((s) => ({ ...s, user: null, initialized: true }));
    },

    async init() {
        const state = get(auth);
        if (state.initialized || state.loading) return;

        auth.update((s) => ({ ...s, loading: true }));

        try {
            const { user } = await getMe();
            auth.update((s) => ({ ...s, user, loading: false, initialized: true }));
        } catch {
            // not logged in (401) or server offline
            auth.update((s) => ({ ...s, user: null, loading: false, initialized: true }));
        }
    }
};
