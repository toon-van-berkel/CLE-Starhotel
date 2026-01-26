import type { PageLoad } from './$types';
import { error as kitError } from '@sveltejs/kit';

export const load: PageLoad = async ({ fetch }) => {
  const res = await fetch(`/api/confirm`);
  if (!res.ok) throw kitError(res.status, res.statusText);

  const json = await res.json();
  return { room: json.record ?? null, error: null };
};