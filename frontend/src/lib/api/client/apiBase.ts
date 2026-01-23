import { PUBLIC_API_BASE } from '$env/static/public';
import type { FetchLike } from '$lib/api/client/apiTypes';

export type ApiError = Error & {
	status?: number;
	data?: unknown;
};

export async function api<ResponseData>(
	fetchFn: FetchLike,
	path: string,
	init: RequestInit = {}
): Promise<ResponseData> {
	const response = await fetchFn(`${PUBLIC_API_BASE}${path}`, {
		...init,
		credentials: 'include',
		cache: 'no-store',
		headers: {
			Accept: 'application/json',
			...(init.body ? { 'Content-Type': 'application/json' } : {}),
			...(init.headers ?? {})
		}
	});

	const jsonData: unknown = await response.json().catch(() => ({}));

	if (!response.ok) {
		const error = new Error(
			(jsonData as any)?.error || `Request failed (${response.status})`
		) as ApiError;

		error.status = response.status;
		error.data = jsonData;

		throw error;
	}

	return jsonData as ResponseData;
}