import { apiCall } from '$lib/api/client/__index__';

export const load = async ({ fetch, params }) => {
    const roomData = await apiCall('room', fetch, { id: Number(params.id) });
    return { roomData };
};
