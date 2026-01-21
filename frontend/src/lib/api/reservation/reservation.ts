import { api } from '$lib/api/client/apiBase';
import type { userInfoReservation } from '$lib/api/reservation/user-reservation-info';

export function getUserInfoReservation(params: userInfoReservation) {
    return api<{ ok: true; params: userInfoReservation }>(`/confirm`, {method: 'GET'});
}