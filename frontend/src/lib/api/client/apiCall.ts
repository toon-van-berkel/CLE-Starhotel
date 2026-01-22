import { endpoints } from "$lib/api/client/apiRoute";
import type { ApiGetMap, FetchLike } from "$lib/api/client/apiTypes";

type GetOutput<K extends keyof ApiGetMap> = ApiGetMap[K]["output"];
type GetInput<K extends keyof ApiGetMap> = ApiGetMap[K] extends {
  input: infer I;
}
  ? I
  : never;

// overloads
export function apiCall<K extends keyof ApiGetMap>(
  key: K,
  fetch: FetchLike
): ApiGetMap[K] extends { input: any } ? never : Promise<GetOutput<K>>;

export function apiCall<K extends keyof ApiGetMap>(
  key: K,
  fetch: FetchLike,
  payload: GetInput<K>
): ApiGetMap[K] extends { input: any } ? Promise<GetOutput<K>> : never;

export function apiCall(key: any, fetch: any, payload?: any) {
  const ep: any = (endpoints as any)[key];
  return payload === undefined ? ep.get(fetch) : ep.get(fetch, payload);
}
