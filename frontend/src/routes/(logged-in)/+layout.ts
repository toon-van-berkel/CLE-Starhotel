import type { LayoutLoad } from './$types';
import { requireLoggedIn } from '$lib/api/auth/guards';

export const load: LayoutLoad = async ({ fetch, url }) => {
	const user = await requireLoggedIn(fetch, `/login?next=${encodeURIComponent(url.pathname)}`);
	return { user };
};
