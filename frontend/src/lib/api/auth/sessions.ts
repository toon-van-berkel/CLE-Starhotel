import { writable, get } from 'svelte/store';
import { browser } from '$app/environment';
import type { FetchLike } from '$lib/api/client/apiTypes';
import type {RefreshMeOptions, MeResponseShape, AuthUser} from '$lib/api/types/user';
import { apiCall } from '$lib/api/client/apiCall';

export const meStore = writable<AuthUser | null>(null);

let inFlightRequest: Promise<AuthUser | null> | null = null;

export async function refreshMe(fetchFn: FetchLike, options: RefreshMeOptions = {}) {
	if (browser && !options.force) {
		const cachedUser = get(meStore);
		if (cachedUser) return cachedUser;
		if (inFlightRequest) return inFlightRequest;
	}

	const requestPromise = (async () => {
		try {
			const response = (await apiCall('me', fetchFn)) as MeResponseShape;
			const user = response?.user ?? null;

			if (browser) meStore.set(user);
			return user;
		} catch {
			if (browser) meStore.set(null);
			return null;
		} finally {
			if (browser) inFlightRequest = null;
		}
	})();

	if (browser) inFlightRequest = requestPromise;
	return requestPromise;
}

export function clearMe() {
	if (browser) meStore.set(null);
}

export function isUserLoggedIn(user: AuthUser | null) {
	return user !== null;
}

export function isUserLoggedOut(user: AuthUser | null) {
	return user === null;
}

// Developer role id = 1 in your DB (roles table)
export function isUserAdmin(user: AuthUser | null) {
	return user?.role_ids.includes(1) ?? false;
}

export function isUserStatus(user: AuthUser | null, statusId: number) {
	return user?.status_id === statusId;
}

export function hasPermission(user: AuthUser | null, permissionId: number) {
	return user?.permission_ids?.includes(permissionId) ?? false;
}
