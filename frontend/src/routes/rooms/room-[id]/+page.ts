import type { PageLoad } from './$types';
import { error as kitError } from '@sveltejs/kit';

export const load: PageLoad = async ({ params, fetch }) => {
  const id = Number(params.id);
  if (!Number.isFinite(id)) return { room: null, error: 'Invalid id' };

  const res = await fetch(`/api/rooms/room-${id}`);
  if (!res.ok) throw kitError(res.status, res.statusText);

  const json = await res.json();
  return { room: json.record ?? null, error: null };
};
