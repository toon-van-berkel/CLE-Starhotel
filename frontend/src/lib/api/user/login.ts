import { api } from '$lib/api/client/api';
import type { User } from '$lib/api/types/__index__';

export function login(email: string, password: string) {
    return api<{ ok: true; user: User }>('/login', {
        method: 'POST',
        body: JSON.stringify({ email, password })
    });
}
