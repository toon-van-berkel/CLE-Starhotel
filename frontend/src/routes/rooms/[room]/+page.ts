import type { PageLoad } from './$types';
import { apiGet } from '$lib/api/api';
import { endpoints } from '$lib/api/endpoints';

function parseRoomId(roomSegment: string): number {
  // "room-123" -> 123
  const numericPart = roomSegment.replace('room-', '');
  return Number(numericPart);
}

export const load: PageLoad = async ({ fetch, params }) => {
  const roomId = parseRoomId(params.room);
  const room = await apiGet<any>(fetch, endpoints.roomDetail(roomId));
  return { roomId, room };
};
