import type { LayoutLoad } from './$types';
import { requireRoleIdThatHasPer } from '$lib/api/auth/guards';

export const load: LayoutLoad = async ({ fetch }) => {
	await requireRoleIdThatHasPer(fetch, 6);
	return {};
};
