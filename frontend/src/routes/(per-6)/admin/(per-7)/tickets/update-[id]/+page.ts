import { apiCall } from '$lib/api/client/__index__';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch, params }) => {
	const id = Number(params.id);
	const contactData = await apiCall('contact', fetch, { id });
	return { id, contactData };
};
