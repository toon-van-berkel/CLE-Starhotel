import type { LayoutLoad } from './$types';
import { refreshMe } from '$lib/api/auth/sessions';

export const ssr = false;
export const prerender = false;

export const load: LayoutLoad = async ({ fetch }) => {
	const user = await refreshMe(fetch);
	return { user };
};
