import type { User } from "$lib/api/types/user";

type ParentFn = () => Promise<{ user: User | null }>;

export async function loadAuth(parent: ParentFn) {
  const { user } = await parent();

  return {
    user,

    isUserLoggedIn: () => user !== null,
    isUserLoggedOut: () => user === null,

    // assuming role_id === 1 is admin
    isUserAdmin: () => user !== null && user.role_id === 1,

    isUserStatus: (...allowed: number[]) =>
      user !== null &&
      user.status_id !== null &&
      allowed.includes(user.status_id),
  };
}
