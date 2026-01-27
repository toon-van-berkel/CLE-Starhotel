import type { PageLoad } from './$types';
import { apiGet } from '$lib/api/api';
import { endpoints } from '$lib/api/endpoints';

function parseRoomId(roomSegment: string): number {
  return Number(roomSegment.replace('room-', ''));
}

function parseBookingId(bookingSegment: string): number {
  // "book-456" -> 456
  return Number(bookingSegment.replace('book-', ''));
}

export const load: PageLoad = async ({ fetch, params }) => {
  const roomId = parseRoomId(params.room);
  const bookingId = parseBookingId(params.booking);

  const room = await apiGet<any>(fetch, endpoints.roomDetail(roomId));

  let reservation: any = null;
  if (bookingId > 0) {
    reservation = await apiGet<any>(fetch, endpoints.reservationDetail(bookingId));
  }

  return { roomId, bookingId, room, reservation };
};
