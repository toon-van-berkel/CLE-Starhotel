import { api } from '$lib/api/client/apiBase';
import type { ApiGetMap, ApiSubmitMap, FetchLike } from '$lib/api/client/apiTypes';

/* ------------------------------------------
 * Keys
 * ------------------------------------------ */
export type ApiGetKey = keyof ApiGetMap;
export type ApiSubmitKey = keyof ApiSubmitMap;
export type ApiEndpointKey = ApiGetKey | ApiSubmitKey;

/* ------------------------------------------
 * Helper types
 * ------------------------------------------ */
export type GetInputForKey<Key extends ApiGetKey> =
	ApiGetMap[Key] extends { input: infer Input } ? Input : undefined;

export type GetFunctionForKey<Key extends ApiGetKey> =
	GetInputForKey<Key> extends undefined
		? (fetch: FetchLike) => Promise<ApiGetMap[Key]['output']>
		: (fetch: FetchLike, payload: GetInputForKey<Key>) => Promise<ApiGetMap[Key]['output']>;

export type SubmitFunctionForKey<Key extends ApiSubmitKey> =
	(fetch: FetchLike, payload: ApiSubmitMap[Key]['input']) => Promise<ApiSubmitMap[Key]['output']>;

/* ------------------------------------------
 * EndpointShape
 * ------------------------------------------ */
export type EndpointShape = {
	[Key in ApiEndpointKey]: {
		get?: Key extends ApiGetKey ? GetFunctionForKey<Key> : never;
		submit?: Key extends ApiSubmitKey ? SubmitFunctionForKey<Key> : never;
	};
};

/* ------------------------------------------
 * endpoints
 * ------------------------------------------ */
export const endpoints = {
	// Room
	rooms: {
		get: (fetch: FetchLike) =>
			api<ApiGetMap['rooms']['output']>(fetch, '/api/rooms', {
				method: 'GET'
			})
	},
	room: {
		get: (fetch: FetchLike, payload: ApiGetMap['room']['input']) =>
			api<ApiGetMap['room']['output']>(fetch, `/api/rooms/room-${payload.id}`, {
				method: 'GET'
			}),

		submit: (fetch: FetchLike, payload: ApiSubmitMap["room"]["input"]) =>
			api<ApiSubmitMap["room"]["output"]>(fetch, "/api/rooms", {
				method: "POST",
				body: JSON.stringify(payload),
			}),
	},

	// Contact
	contacts: {
		get: (fetch: FetchLike) =>
			api<ApiGetMap['contacts']['output']>(fetch, '/api/contact', {
				method: 'GET'
			})
	},
	contact: {
		get: (fetch: FetchLike, payload: ApiGetMap['contact']['input']) =>
			api<ApiGetMap['contact']['output']>(fetch, `/api/contact/contact-${payload.id}`, {
				method: 'GET'
			}),

		submit: (fetch: FetchLike, payload: ApiSubmitMap['contact']['input']) =>
			api<ApiSubmitMap['contact']['output']>(fetch, '/api/contact', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	contactUpdate: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['contactUpdate']['input']) =>
			api<ApiSubmitMap['contactUpdate']['output']>(fetch, '/api/contact/update', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	contactDelete: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['contactDelete']['input']) =>
			api<ApiSubmitMap['contactDelete']['output']>(fetch, '/api/contact/delete', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},

	// Reservations
	reservations: {
		get: (fetch: FetchLike) =>
			api<ApiGetMap['reservations']['output']>(fetch, '/api/reservation', {
				method: 'GET'
			})
	},
	reservation: {
		get: (fetch: FetchLike, payload: ApiGetMap['reservation']['input']) =>
			api<ApiGetMap['reservation']['output']>(fetch, `/api/reservation/reservation-${payload.id}`, {
				method: 'GET'
		}),

		submit: (fetch: FetchLike, payload: ApiSubmitMap['reservation']['input']) =>
			api<ApiSubmitMap['reservation']['output']>(fetch, '/api/reservation', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	reservationUpdate: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['reservationUpdate']['input']) =>
			api<ApiSubmitMap['reservationUpdate']['output']>(fetch, '/api/reservation/update', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	reservationDelete: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['reservationDelete']['input']) =>
			api<ApiSubmitMap['reservationDelete']['output']>(fetch, '/api/reservation/delete', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},

	// Auth
	me: {
		get: (fetch: FetchLike) =>
			api<ApiGetMap['me']['output']>(fetch, '/api/me', {
				method: 'GET'
			})
	},
	login: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['login']['input']) =>
			api<ApiSubmitMap['login']['output']>(fetch, '/api/login', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	register: {
		submit: (fetch: FetchLike, payload: ApiSubmitMap['register']['input']) =>
			api<ApiSubmitMap['register']['output']>(fetch, '/api/register', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},
	logout: {
		submit: (fetch: FetchLike, _payload: ApiSubmitMap['logout']['input']) =>
			api<ApiSubmitMap['logout']['output']>(fetch, '/api/logout', {
				method: 'POST'
			})
	}
} satisfies EndpointShape;
