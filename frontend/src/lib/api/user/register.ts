import { api } from '$lib/api/client/api';
import type { RegisterPayload } from '$lib/api/types/user';

export function register(payload: RegisterPayload) {
    return api<{ ok: true }>('/register', {
        method: 'POST',
        body: JSON.stringify(payload)
    });
}
