import type { PageLoad } from './$types';
import { detailPage } from '$lib/api/room/records';

export const load: PageLoad = async ({ params }) => {
  const id = Number(params.id);
  if (!Number.isFinite(id)) {
    return { room: null, error: 'Invalid id' };
  }

  const res = await detailPage(id);
  return { room: res.record, error: res.error ?? null };
};