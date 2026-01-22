export const ssr = false;
export const prerender = false;

import type { LayoutLoad } from './$types';
import { refreshMe } from '$lib/api/auth/sessions';

export const load: LayoutLoad = async ({ fetch }) => {
  const user = await refreshMe(fetch, { force: true });
  return { user };
};
