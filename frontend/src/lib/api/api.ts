import { PUBLIC_API_BASE } from '$env/static/public';

export type FetchFunction = (input: RequestInfo | URL, init?: RequestInit) => Promise<Response>;

type JsonValue = unknown;

function buildUrl(path: string) {
  const base = (PUBLIC_API_BASE ?? '').replace(/\/$/, '');
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  return `${base}${cleanPath}`;
}

async function parseResponseBody(response: Response): Promise<JsonValue> {
  const text = await response.text();
  if (!text) return null;

  try {
    return JSON.parse(text);
  } catch {
    return text;
  }
}

export async function apiRequest<T>(
  fetchFunction: FetchFunction,
  path: string,
  requestOptions: RequestInit = {}
): Promise<T> {
  const response = await fetchFunction(buildUrl(path), {
    credentials: 'include',
    ...requestOptions,
    headers: {
      'Content-Type': 'application/json',
      ...(requestOptions.headers ?? {})
    }
  });

  const body = await parseResponseBody(response);

  if (!response.ok) {
    const message =
      typeof body === 'object' && body !== null && ('message' in body || 'error' in body)
        ? String((body as any).message ?? (body as any).error)
        : `Request failed (${response.status})`;
    throw new Error(message);
  }

  return body as T;
}

export function apiGet<T>(fetchFunction: FetchFunction, path: string) {
  return apiRequest<T>(fetchFunction, path, { method: 'GET' });
}

export function apiPost<T>(fetchFunction: FetchFunction, path: string, data: unknown) {
  return apiRequest<T>(fetchFunction, path, { method: 'POST', body: JSON.stringify(data) });
}

export function apiPut<T>(fetchFunction: FetchFunction, path: string, data: unknown) {
  return apiRequest<T>(fetchFunction, path, { method: 'PUT', body: JSON.stringify(data) });
}

export function apiPatch<T>(fetchFunction: FetchFunction, path: string, data: unknown) {
  return apiRequest<T>(fetchFunction, path, { method: 'PATCH', body: JSON.stringify(data) });
}

export function apiDelete<T>(fetchFunction: FetchFunction, path: string) {
  return apiRequest<T>(fetchFunction, path, { method: 'DELETE' });
}
