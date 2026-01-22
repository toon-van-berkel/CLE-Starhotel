import { PUBLIC_API_BASE } from '$env/static/public';
import type { FetchLike } from '$lib/api/client/apiTypes';

export async function api<T>(fetchFn: FetchLike, path: string, init: RequestInit = {}): Promise<T> {
	// console.log(`${PUBLIC_API_BASE}${path}`);

	const res = await fetchFn(`${PUBLIC_API_BASE}${path}`, {
		...init,
		credentials: 'include',
		cache: 'no-store',
		headers: {
			Accept: 'application/json',
			...(init.body ? { 'Content-Type': 'application/json' } : {}),
			...(init.headers || {})
		}
	});

	const data = await res.json().catch(() => ({}));

	if (!res.ok) {
		const err: any = new Error((data as any)?.error || `Request failed (${res.status})`);
		err.status = res.status;
		err.data = data;
		throw err;
	}

	return data as T;
}