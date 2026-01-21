import type { Handle } from '@sveltejs/kit';
import { PUBLIC_API_BASE } from '$env/static/public';

const HOP_BY_HOP = new Set([
    'connection',
    'keep-alive',
    'proxy-authenticate',
    'proxy-authorization',
    'te',
    'trailers',
    'transfer-encoding',
    'upgrade',
    'host'
]);

function forwardHeaders(request: Request) {
    const headers = new Headers();
    for (const [k, v] of request.headers.entries()) {
        const key = k.toLowerCase();
        if (!HOP_BY_HOP.has(key)) headers.set(k, v);
    }
    return headers;
}

export const handle: Handle = async ({ event, resolve }) => {
	const { url, request, isDataRequest } = event;

	// ⛔ SSR mag NIET door de proxy
	if (url.pathname.startsWith('/api/') && !event.route?.id) {
		const upstreamUrl = `${PUBLIC_API_BASE}${url.pathname}${url.search}`;

		const headers = forwardHeaders(request);
		const cookie = request.headers.get('cookie');
		if (cookie) headers.set('cookie', cookie);

		const upstreamRes = await globalThis.fetch(upstreamUrl, {
			method: request.method,
			headers,
			body: ['GET', 'HEAD'].includes(request.method)
				? undefined
				: await request.arrayBuffer()
		});

		return new Response(upstreamRes.body, {
			status: upstreamRes.status,
			headers: upstreamRes.headers
		});
	}

	return resolve(event);
};

