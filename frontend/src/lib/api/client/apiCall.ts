import { endpoints } from "$lib/api/client/apiRoute";
import type { ApiGetMap, FetchLike } from "$lib/api/client/apiTypes";

export type ApiGetKey = keyof ApiGetMap;

export type GetOutputForKey<Key extends ApiGetKey> = ApiGetMap[Key]['output'];
export type GetInputForKey<Key extends ApiGetKey> =
	ApiGetMap[Key] extends { input: infer Input } ? Input : undefined;

// overloads
export function apiCall<Key extends ApiGetKey>(
	key: Key,
	fetch: FetchLike
): GetInputForKey<Key> extends undefined ? Promise<GetOutputForKey<Key>> : never;

export function apiCall<Key extends ApiGetKey>(
	key: Key,
	fetch: FetchLike,
	payload: GetInputForKey<Key>
): Promise<GetOutputForKey<Key>>;

export function apiCall<Key extends ApiGetKey>(
	key: Key,
	fetch: FetchLike,
	payload?: GetInputForKey<Key>
) {
	const endpoint = (endpoints as Record<ApiGetKey, any>)[key];
	return payload !== undefined ? endpoint.get(fetch, payload) : endpoint.get(fetch);
}
