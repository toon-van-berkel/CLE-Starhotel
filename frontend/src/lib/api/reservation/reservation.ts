// import { api } from '$lib/api/client/apiBase';
// import type { userInfoReservation } from '$lib/api/reservation/user-reservation-info';
// import type { upload } from '$lib/api/reservation/user-reservation-info';
// import type { uploadTest } from '$lib/api/reservation/user-reservation-info';

// export function getUserInfoReservation(params: userInfoReservation) {
//     return api<{ ok: true; params: userInfoReservation }>(`/confirm`, {method: 'GET'});
// }

// export function upload(params: upload) {
//     return api<{ ok: true; params: upload }>(`/confirm`, {method: 'GET'});
// }

// export function uploadTest(params: uploadTest) {
//     return api<{ ok: true; params: uploadTest }>(`/confirm`, {method: 'GET'});
// }


import { api } from '$lib/api/client/apiBase';
import type { FetchLike } from '$lib/api/client/apiTypes';

import type {
    userInfoReservation as UserInfoReservationParams,
    upload as UploadParams,
    uploadTest as UploadTestParams
} from '$lib/api/reservation/user-reservation-info';

const CONFIRM_PATH = '/api/confirm';

function toQuery(params: Record<string, unknown>) {
    const qs = new URLSearchParams();
    for (const [k, v] of Object.entries(params ?? {})) {
        if (v === undefined || v === null) continue;
        qs.set(k, String(v));
    }
    const s = qs.toString();
    return s ? `?${s}` : '';
}

export function getUserInfoReservation(fetch: FetchLike, params: UserInfoReservationParams) {
    return api<{ ok: true; params: UserInfoReservationParams }>(
        fetch,
        `${CONFIRM_PATH}${toQuery(params as any)}`,
        { method: 'GET' }
    );
}

export function upload(fetch: FetchLike, params: UploadParams) {
    return api<{ ok: true; params: UploadParams }>(
        fetch,
        `${CONFIRM_PATH}${toQuery(params as any)}`,
        { method: 'GET' }
    );
}

export function uploadTest(fetch: FetchLike, params: UploadTestParams) {
    return api<{ ok: true; params: UploadTestParams }>(
        fetch,
        `${CONFIRM_PATH}${toQuery(params as any)}`,
        { method: 'GET' }
    );
}