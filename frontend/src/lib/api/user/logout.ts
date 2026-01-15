import { api } from '$lib/api/client/api';

export function logout() {
    return api<{ ok: true }>('/logout', { method: 'POST' });
}
