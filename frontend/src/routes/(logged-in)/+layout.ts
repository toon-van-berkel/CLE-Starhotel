import type { LayoutLoad } from './$types';
import { requireLoggedIn } from '$lib/api/auth/guards';

export const load: LayoutLoad = async ({ fetch }) => {
	const user = await requireLoggedIn(fetch);
	return { user };
};
