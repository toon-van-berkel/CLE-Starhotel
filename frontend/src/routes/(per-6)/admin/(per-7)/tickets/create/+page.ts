import { requireRoleIdThatHasPer } from '$lib/api/auth/guards';

export const load = async ({ fetch }) => {
    await requireRoleIdThatHasPer(fetch, 11);
    return {};
};
