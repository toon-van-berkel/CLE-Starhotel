import { api } from '$lib/api/client/apiBase';
import type { ApiGetMap, ApiSubmitMap, FetchLike } from '$lib/api/client/apiTypes';

export type EndpointShape = {
	[K in keyof ApiGetMap | keyof ApiSubmitMap]: {
		get?: K extends keyof ApiGetMap
			? ApiGetMap[K] extends { input: infer I }
				? (fetch: FetchLike, payload: I) => Promise<ApiGetMap[K]['output']>
				: (fetch: FetchLike) => Promise<ApiGetMap[K]['output']>
			: never;

		submit?: K extends keyof ApiSubmitMap
			? (fetch: FetchLike, payload: ApiSubmitMap[K]['input']) => Promise<ApiSubmitMap[K]['output']>
			: never;
	};
};

export const endpoints = {
	// Room
	rooms: {
		get: (fetch: FetchLike) => api<ApiGetMap['rooms']['output']>(fetch, '/api/rooms', { method: 'GET' })
	},
	room: {
		get: (fetch: FetchLike, payload: ApiGetMap['room']['input']) =>
			api<ApiGetMap['room']['output']>(fetch, `/api/rooms/room-${payload.id}`, { method: 'GET' }),

		submit: (fetch: FetchLike, payload: ApiSubmitMap['room']['input']) =>
			api<ApiSubmitMap['room']['output']>(fetch, '/api/rooms', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},

	// Contact
	contacts: {
		get: (fetch: FetchLike) => api<ApiGetMap['contacts']['output']>(fetch, '/api/contact', { method: 'GET' })
	},
	contact: {
		get: (fetch: FetchLike, payload: ApiGetMap['contact']['input']) =>
			api<ApiGetMap['contact']['output']>(fetch, `/api/contact/contact-${payload.id}`, { method: 'GET' }),

		submit: (fetch: FetchLike, payload: ApiSubmitMap['contact']['input']) =>
			api<ApiSubmitMap['contact']['output']>(fetch, '/api/contact', {
				method: 'POST',
				body: JSON.stringify(payload)
			})
	},

	// Auth
	me: {
		get: (fetch: FetchLike) =>
			api<ApiGetMap['me']['output']>(fetch, '/api/me', { method: 'GET' })
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
		submit: (fetch: FetchLike) =>
			api<ApiSubmitMap['logout']['output']>(fetch, '/api/logout', { method: 'POST' })
	}
} satisfies EndpointShape;