import { api } from '$lib/api/client/api';
import type { reservationDetails } from '$lib/api/confirm/reservationdetails';

export function getReservationDetails(room_id: number, totalPeople: number, booked_from: string, booked_till: string) {
    return api<{ ok: true; details: reservationDetails }>('/confirm', {method: 'GET'});
}