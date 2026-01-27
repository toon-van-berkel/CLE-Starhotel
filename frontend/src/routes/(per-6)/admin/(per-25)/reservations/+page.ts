import { apiCall } from '$lib/api/client/__index__';

export const load = async ({ fetch }) => {
    const reservationsData = await apiCall('reservations', fetch);
    return { reservationsData };
};