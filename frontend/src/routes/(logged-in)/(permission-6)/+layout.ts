import type { LayoutLoad } from './$types';
import { requirePermission } from '$lib/guards/guards';

export const load: LayoutLoad = async ({ parent }) => {
  const { user } = await parent();
  requirePermission(user, 6);
  return {};
};
