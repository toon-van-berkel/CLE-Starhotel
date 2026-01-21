import { writable, get } from 'svelte/store';
import type { FetchLike } from '$lib/api/client/apiTypes';
import { apiCall } from '$lib/api/client/apiCall';

// Match what your backend returns from /api/me
export type AuthUser = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	status_id: number | null;

	// IMPORTANT with your DB (many-to-many)
	role_ids: number[];

	// Add this once backend provides it
	permission_ids?: number[];
};

export const meStore = writable<AuthUser | null>(null);

let inFlight: Promise<AuthUser | null> | null = null;

/**
 * Fetch current session user.
 * - returns user on success
 * - returns null on 401/any error
 * - caches in a store
 */
export async function refreshMe(fetchFn: FetchLike, opts: { force?: boolean } = {}) {
	if (!opts.force) {
		const cached = get(meStore);
		if (cached) return cached;
		if (inFlight) return inFlight;
	}

	inFlight = (async () => {
		try {
			const res = await apiCall('me', fetchFn);
			// Expecting: { ok: true, user: AuthUser|null } OR { user: AuthUser|null }
			const user = (res as any).user ?? null;
			meStore.set(user);
			return user as AuthUser | null;
		} catch {
			meStore.set(null);
			return null;
		} finally {
			inFlight = null;
		}
	})();

	return inFlight;
}

export function clearMe() {
	meStore.set(null);
}


export function isUserLoggedIn(user: AuthUser | null) {
	return !!user;
}

export function isUserLoggedOut(user: AuthUser | null) {
	return !user;
}

// Developer role id = 1 in your DB (roles table)
export function isUserAdmin(user: AuthUser | null) {
	return !!user && user.role_ids.includes(1);
}

export function isUserStatus(user: AuthUser | null, statusId: number) {
	return !!user && user.status_id === statusId;
}

export function hasPermission(user: AuthUser | null, perId: number) {
	return !!user && Array.isArray(user.permission_ids) && user.permission_ids.includes(perId);
}
