import { apiCall } from '$lib/api/client/apiCall';

export const load = async ({ fetch, params }) => {
	const id = Number(params.id);
	const reservationData = await apiCall('reservation', fetch, { id });
	return { id, reservationData };
};
