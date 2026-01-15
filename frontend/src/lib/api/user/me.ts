import { api } from '$lib/api/client/api';
import type { User } from '$lib/api/types/user';

export function getMe() {
    return api<{ user: User }>('/me', { method: 'GET' });
}
