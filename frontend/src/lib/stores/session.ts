import { writable } from 'svelte/store';
import type { FetchFunction } from '$lib/api/api';
import { apiGet, apiPost } from '$lib/api/api';
import { endpoints } from '$lib/api/endpoints';

export type AuthUser = {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  status_id?: number | null;
  role_ids?: number[];
  permission_ids?: number[];
};

export const userStore = writable<AuthUser | null>(null);

let userRequestInProgress: Promise<AuthUser | null> | null = null;

export async function refreshCurrentUser(fetchFunction: FetchFunction): Promise<AuthUser | null> {
  if (userRequestInProgress) return userRequestInProgress;

  userRequestInProgress = (async () => {
    try {
      const response: any = await apiGet<any>(fetchFunction, endpoints.me);
      const user: AuthUser | null = response?.user ?? response ?? null;
      userStore.set(user);
      return user;
    } catch {
      userStore.set(null);
      return null;
    } finally {
      userRequestInProgress = null;
    }
  })();

  return userRequestInProgress;
}

export async function login(fetchFunction: FetchFunction, credentials: { email: string; password: string }) {
  const response = await apiPost<any>(fetchFunction, endpoints.login, credentials);
  await refreshCurrentUser(fetchFunction);
  return response;
}

export async function register(fetchFunction: FetchFunction, data: { first_name: string; last_name: string; email: string; password: string }) {
  const response = await apiPost<any>(fetchFunction, endpoints.register, data);
  await refreshCurrentUser(fetchFunction);
  return response;
}

export async function logout(fetchFunction: FetchFunction) {
  const response = await apiPost<any>(fetchFunction, endpoints.logout, {});
  userStore.set(null);
  return response;
}
