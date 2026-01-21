import { apiCall } from '$lib/api/client/__index__';

export const load = async ({ fetch }) => {
    const roomsData = await apiCall('rooms', fetch);
    return { roomsData };
};


