import { api } from '$lib/api/client/apiBase';
import type { ApiGetMap, ApiSubmitMap, FetchLike } from '$lib/api/client/apiTypes';

export type EndpointShape = {
    [K in (keyof ApiGetMap | keyof ApiSubmitMap)]: {
        get?: K extends keyof ApiGetMap
            ? (ApiGetMap[K] extends { input: infer I }
                ? (fetch: FetchLike, payload: I) => Promise<ApiGetMap[K]['output']>
                : (fetch: FetchLike) => Promise<ApiGetMap[K]['output']>)
            : never;

        submit?: K extends keyof ApiSubmitMap
            ? (fetch: FetchLike, payload: ApiSubmitMap[K]['input']) => Promise<ApiSubmitMap[K]['output']>
            : never;
    };
};

export const endpoints = {
    rooms: {
        get: (fetch: FetchLike) =>
            api<ApiGetMap['rooms']['output']>(fetch, '/api/rooms', { method: 'GET' })
    },

    room: {
        get: (fetch: FetchLike, payload: ApiGetMap['room']['input']) =>
            api<ApiGetMap['room']['output']>(fetch, `/api/rooms/room-${payload.id}`, { method: 'GET' }),

        submit: (fetch: FetchLike, payload: ApiSubmitMap['room']['input']) =>
            api<ApiSubmitMap['room']['output']>(fetch, '/api/rooms', {
                method: 'POST',
                body: JSON.stringify(payload)
            })
    }
} satisfies EndpointShape;
