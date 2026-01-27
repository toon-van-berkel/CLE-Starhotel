import type { LayoutLoad } from './$types';
import { refreshCurrentUser } from '$lib/stores/session';

export const ssr = false;
export const prerender = false;

export const load: LayoutLoad = async ({ fetch }) => {
  const user = await refreshCurrentUser(fetch);
  return { user };
};
