import { api } from '$lib/api/client/apiBase';
import type { userInfoReservation } from '$lib/api/reservation/user-reservation-info';
import type { upload } from '$lib/api/reservation/user-reservation-info';

export function getUserInfoReservation(params: userInfoReservation) {
    return api<{ ok: true; params: userInfoReservation }>(`/confirm`, {method: 'GET'});
}

export function upload(params: upload) {
    return api<{ ok: true; params: upload }>(`/confirm`, {method: 'GET'});
}