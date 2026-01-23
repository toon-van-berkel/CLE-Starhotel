import { endpoints } from "$lib/api/client/apiRoute";
import type { FetchLike, ApiSubmitMap } from "$lib/api/client/apiTypes";

export type ApiSubmitKey = keyof ApiSubmitMap;

export type SubmitInputForKey<Key extends ApiSubmitKey> = ApiSubmitMap[Key]['input'];
export type SubmitOutputForKey<Key extends ApiSubmitKey> = ApiSubmitMap[Key]['output'];

export type SubmitEndpointsShape = {
	[Key in ApiSubmitKey]: {
		submit: (fetch: FetchLike, payload: SubmitInputForKey<Key>) => Promise<SubmitOutputForKey<Key>>;
	};
};

export const submitEndpoints = endpoints as unknown as SubmitEndpointsShape;

export function apiSubmit<Key extends ApiSubmitKey>(
	key: Key,
	fetch: FetchLike,
	payload: SubmitInputForKey<Key>
): Promise<SubmitOutputForKey<Key>> {
	return submitEndpoints[key].submit(fetch, payload);
}
