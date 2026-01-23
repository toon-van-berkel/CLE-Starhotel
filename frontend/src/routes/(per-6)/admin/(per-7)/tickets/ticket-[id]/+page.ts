import { apiCall } from '$lib/api/client/__index__';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch, params }) => {
    const contactData = await apiCall('contact', fetch, { id: Number(params.id) });
    return { contactData };
};
