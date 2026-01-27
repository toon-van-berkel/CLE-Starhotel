import type { PageLoad } from './$types';
import { apiGet } from '$lib/api/api';
import { endpoints } from '$lib/api/endpoints';

export const load: PageLoad = async ({ fetch }) => {
  const rooms = await apiGet<any>(fetch, endpoints.roomsList);
  return { rooms };
};
