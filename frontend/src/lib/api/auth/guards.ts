import type { User } from '$lib/api/types/__index__';

export function userIsLoggedIn(user: User | null): boolean {
    return user !== null;
}

// optional admin check (adjust role_id)
export function userIsAdmin(user: User | null): boolean {
    return !!user && user.role_id === 1;
}
