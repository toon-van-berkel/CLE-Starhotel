import type { LayoutLoad } from './$types';
import { requireLoggedOut } from '$lib/api/auth/guards';

export const load: LayoutLoad = async ({ fetch }) => {
	await requireLoggedOut(fetch);
	return {};
};
