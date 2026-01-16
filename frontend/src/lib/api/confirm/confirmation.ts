import { api } from '$lib/api/client/api';
import type { reservationDetails } from '$lib/api/confirm/reservationdetails';

export function getReservationDetails(name: string, totalPeople: number, bookedFrom: string, bookedTill: string) {
    const params = new URLSearchParams({
        name: name,
        totalPeople: totalPeople.toString(),
        booked_from: bookedFrom,
        booked_till: bookedTill
    });
    
    return api<{ ok: true; details: reservationDetails }>(`/confirm?${params}`, {method: 'GET'});
}



// export function getReservationDetails(roomId: number, totalPeople: number, bookedFrom: string, bookedTill: string) {
//     return api<{ ok: true; details: reservationDetails }>('/confirm', {method: 'GET'});
// }