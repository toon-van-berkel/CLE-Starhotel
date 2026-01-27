import { redirect } from '@sveltejs/kit';
import type { AuthUser } from '$lib/stores/session';

export function requireLoggedIn(user: AuthUser | null) {
  if (!user) {
    throw redirect(303, '/login');
  }
}

export function requirePermission(user: AuthUser | null, requiredPermissionId: number) {
  requireLoggedIn(user);

  const permissions = user?.permission_ids ?? [];
  const hasPermission = permissions.includes(requiredPermissionId);

  if (!hasPermission) {
    throw redirect(303, '/profile');
  }
}
