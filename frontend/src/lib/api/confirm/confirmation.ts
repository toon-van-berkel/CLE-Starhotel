import { api } from '$lib/api/client/api';
import type { reservationDetails } from '$lib/api/confirm/reservationdetails';

export function getReservationDetails(params: reservationDetails) {
    return api<{ ok: true; params: reservationDetails }>(`/confirm`, {method: 'GET'});
}