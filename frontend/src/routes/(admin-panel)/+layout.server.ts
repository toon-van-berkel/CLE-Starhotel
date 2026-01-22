import type { LayoutServerLoad } from './$types';
import { requireRole } from '$lib/api/auth/guards';

export const load: LayoutServerLoad = async ({ fetch }) => {
	await requireRole(fetch, 1);
	return {};
};
