import { writable, get } from 'svelte/store';
import { browser } from '$app/environment';
import type { FetchLike } from '$lib/api/client/apiTypes';
import { apiCall } from '$lib/api/client/apiCall';

export type AuthUser = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	status_id: number | null;
	role_ids: number[];
	permission_ids?: number[];
};

export const meStore = writable<AuthUser | null>(null);

let inFlight: Promise<AuthUser | null> | null = null;

export async function refreshMe(fetchFn: FetchLike, opts: { force?: boolean } = {}) {
	if (browser && !opts.force) {
		const cached = get(meStore);
		if (cached) return cached;
		if (inFlight) return inFlight;
	}

	const run = (async () => {
		try {
			const res: any = await apiCall('me', fetchFn);
			const user = res?.user ?? null;

			if (browser) meStore.set(user);
			return user as AuthUser | null;
		} catch {
			if (browser) meStore.set(null);
			return null;
		} finally {
			if (browser) inFlight = null;
		}
	})();

	if (browser) inFlight = run;
	return run;
}

export function clearMe() {
	if (browser) meStore.set(null);
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
