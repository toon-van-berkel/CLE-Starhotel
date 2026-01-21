import { endpoints } from "$lib/api/client/apiRoute";
import type { FetchLike, ApiSubmitMap } from "$lib/api/client/apiTypes";

type SubmitEndpoints = {
  [K in keyof ApiSubmitMap]: {
    submit: (
      fetch: FetchLike,
      payload: ApiSubmitMap[K]["input"]
    ) => Promise<ApiSubmitMap[K]["output"]>;
  };
};

const submitEndpoints = endpoints as unknown as SubmitEndpoints;

export function apiSubmit<K extends keyof ApiSubmitMap>(
  key: K,
  fetch: FetchLike,
  payload: ApiSubmitMap[K]["input"]
): Promise<ApiSubmitMap[K]["output"]> {
  return submitEndpoints[key].submit(fetch, payload);
}
