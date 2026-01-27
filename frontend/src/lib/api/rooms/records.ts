import type { Room } from '$lib/api/types/room';
import { PUBLIC_API_BASE } from '$env/static/public';

export type RoomDetailResponse = {
  record: Room | null;
  error?: string;
};

export async function getDetailPage(id: number, fetchFn: typeof fetch) {
  const res = await fetchFn(`${PUBLIC_API_BASE}/api/rooms/room-${id}`, {
    method: 'GET',
    headers: { Accept: 'application/json' }
  });
  
  if (!res.ok) {
    return { record: null, error: `${res.status} ${res.statusText}` } satisfies RoomDetailResponse;
  }

  return (await res.json()) as RoomDetailResponse;
}
