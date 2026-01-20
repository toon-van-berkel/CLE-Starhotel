import type { Handle } from '@sveltejs/kit';
import { API_BASE } from '$env/static/private';

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
    const { url, request, fetch } = event;

    // Only proxy /api/*
    if (url.pathname.startsWith('/api/')) {
        const upstreamUrl = `${API_BASE}${url.pathname}${url.search}`;

        const upstreamRes = await fetch(upstreamUrl, {
        method: request.method,
        headers: forwardHeaders(request),
        body: ['GET', 'HEAD'].includes(request.method)
            ? undefined
            : await request.arrayBuffer()
        });

        // Return upstream response (streamed), keep status + headers
        return new Response(upstreamRes.body, {
        status: upstreamRes.status,
        headers: upstreamRes.headers
        });
    }

    // All non-API requests behave normally
    return resolve(event);
};