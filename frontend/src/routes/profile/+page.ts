import type { PageLoad } from './$types';
import { requireLoggedIn } from '$lib/api/auth/guards';

export const load: PageLoad = async ({ fetch }) => {
	const user = await requireLoggedIn(fetch);
	return { user };
};
