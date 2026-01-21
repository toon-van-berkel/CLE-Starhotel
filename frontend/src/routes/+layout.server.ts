import type { LayoutServerLoad } from './$types';
import { apiCall } from '$lib/api/client/apiCall';

export const load: LayoutServerLoad = async ({ fetch }) => {
	try {
		const me = await apiCall('me', fetch);
		return { user: me.user };
	} catch {
		return { user: null };
	}
};
