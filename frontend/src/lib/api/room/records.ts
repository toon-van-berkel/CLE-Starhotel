import { api } from '$lib/api/client/api';
import type { Room } from '$lib/api/types/__index__';

export type RoomsResponse = {
    records: Room[];
    error?: string;
};

export function getRooms() {
    // Als jouw api() helper al "/api" prefix toevoegt, wordt dit: /api/rooms
    return api<RoomsResponse>('/rooms', { method: 'GET' });
}

// Optioneel: backward compatibility (als je oude calls nog gebruikt)
export const records = getRooms;
    

// export function detailPage(id: number) {
//     return api<{ room: Room['id'] }>('/detail', {
//         method: 'POST',
//         body: JSON.stringify({ id })
//     });
// }

export type RoomDetailResponse = {
  record: Room | null;
  error?: string;
};

export function detailPage(id: number) {
  return api<RoomDetailResponse>('/detail', {
    method: 'POST',
    body: JSON.stringify({ id })
  });
}

export function getDetailPage(id: number) {
  return api<RoomDetailResponse>(`/rooms/${id}`, {method: "GET"});
}


// export const record = getRoomDetails();