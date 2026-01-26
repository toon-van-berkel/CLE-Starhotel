import { redirect } from '@sveltejs/kit';
import type { FetchLike } from '$lib/api/client/apiTypes';
import { refreshMe } from '$lib/api/auth/sessions';

export async function requireLoggedIn(fetchFn: FetchLike, to = '/login') {
	const user = await refreshMe(fetchFn);
	if (!user) throw redirect(303, to);
	return user;
}
export async function requireLoggedOut(fetchFn: FetchLike, to = '/profile') {
	const user = await refreshMe(fetchFn);
	if (user) throw redirect(303, to);
}

// Permission check (redirect instead of error)
export async function requireRoleIdThatHasPer(fetchFn: FetchLike, perId: number, to = '/403') {
	const user = await requireLoggedIn(fetchFn);
	if (!user.permission_ids?.includes(perId)) throw redirect(303, to);
	return user;
}
export async function requireAnyPermission(fetchFn: FetchLike, perIds: number[], to = '/403') {
	const user = await requireLoggedIn(fetchFn);
	const ok = !!user.permission_ids?.some((p) => perIds.includes(p));
	if (!ok) throw redirect(303, to);
	return user;
}
export async function requireRole(fetchFn: FetchLike, roleId: number, to = '/403') {
	const user = await requireLoggedIn(fetchFn);
	if (!user.role_ids?.includes(roleId)) throw redirect(303, to);
	return user;
}