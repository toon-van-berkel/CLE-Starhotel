import { apiCall } from '$lib/api/client/__index__';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch, params }) => {
    const reservationsData = await apiCall('reservation', fetch, { id: Number(params.id) });
    return { reservationsData };
};
