import { apiCall } from '$lib/api/client/apiCall';

export const load = async ({ fetch, params }) => {
	const id = Number(params.id);

	const reservationData = await apiCall('reservation', fetch, { id });
	const roomsData = await apiCall('rooms', fetch);

	return { id, reservationData, roomsData };
};
