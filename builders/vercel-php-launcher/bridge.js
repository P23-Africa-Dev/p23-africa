'use strict';

process.env.LAMBDA_TASK_ROOT = process.env.LAMBDA_TASK_ROOT || '/var/task';

const orig = require('./launcher-orig.js');

function headerValue(headers, name) {
    if (!headers) {
        return undefined;
    }

    if (typeof headers.get === 'function') {
        return headers.get(name) || undefined;
    }

    const target = name.toLowerCase();

    for (const [key, value] of Object.entries(headers)) {
        if (key.toLowerCase() === target) {
            return Array.isArray(value) ? value[0] : value;
        }
    }

    return undefined;
}

function plainHeaders(headers) {
    const next = {};

    if (!headers) {
        return next;
    }

    if (typeof headers.forEach === 'function' && typeof headers.get === 'function') {
        headers.forEach((value, key) => {
            next[key.toLowerCase()] = value;
        });

        return next;
    }

    for (const [key, value] of Object.entries(headers)) {
        next[key.toLowerCase()] = value;
    }

    return next;
}

function withoutHopHeaders(headers, body) {
    const next = plainHeaders(headers);
    delete next['transfer-encoding'];
    delete next.connection;
    delete next['content-length'];

    if (body.length > 0) {
        next['content-length'] = String(body.length);
    }

    return next;
}

async function readBody(body, isBase64) {
    if (body == null || body === '') {
        return Buffer.alloc(0);
    }

    if (Buffer.isBuffer(body)) {
        return body;
    }

    if (body instanceof Uint8Array) {
        return Buffer.from(body);
    }

    if (Array.isArray(body)) {
        return Buffer.from(body);
    }

    if (typeof body === 'string') {
        return Buffer.from(body, isBase64 ? 'base64' : 'utf8');
    }

    if (body && body.type === 'Buffer' && Array.isArray(body.data)) {
        return Buffer.from(body.data);
    }

    if (typeof body.arrayBuffer === 'function') {
        return Buffer.from(await body.arrayBuffer());
    }

    if (typeof body[Symbol.asyncIterator] === 'function') {
        const chunks = [];

        for await (const chunk of body) {
            chunks.push(Buffer.isBuffer(chunk) ? chunk : Buffer.from(chunk));
        }

        return Buffer.concat(chunks);
    }

    return Buffer.alloc(0);
}

function isWebRequest(value) {
    return Boolean(
        value
        && typeof value.url === 'string'
        && typeof value.method === 'string'
        && value.headers
        && typeof value.headers.get === 'function'
        && typeof value.arrayBuffer === 'function'
    );
}

function isNodeResponse(value) {
    return Boolean(value && typeof value.writeHead === 'function' && typeof value.end === 'function');
}

async function eventFromWebRequest(request) {
    const url = new URL(request.url);
    const body = await readBody(request);

    return {
        httpMethod: request.method,
        path: `${url.pathname}${url.search}`,
        host: headerValue(request.headers, 'x-forwarded-host') || headerValue(request.headers, 'host') || url.host,
        headers: withoutHopHeaders(request.headers, body),
        body: body.length ? body : undefined,
    };
}

async function eventFromNodeRequest(request) {
    const body = await readBody(request);
    const headers = withoutHopHeaders(request.headers, body);

    return {
        httpMethod: request.method || 'GET',
        path: request.url || '/',
        host: headerValue(headers, 'x-forwarded-host') || headerValue(headers, 'host'),
        headers,
        body: body.length ? body : undefined,
    };
}

function decodeResultBody(result) {
    const body = result && result.body;

    if (body == null) {
        return Buffer.alloc(0);
    }

    if (Buffer.isBuffer(body)) {
        return body;
    }

    if (result.encoding === 'base64' && typeof body === 'string') {
        return Buffer.from(body, 'base64');
    }

    return Buffer.from(String(body));
}

function responseFromResult(result) {
    const headers = new Headers();
    const raw = (result && result.headers) || {};

    for (const [key, value] of Object.entries(raw)) {
        if (Array.isArray(value)) {
            value.forEach((item) => headers.append(key, String(item)));
        } else if (value != null) {
            headers.set(key, String(value));
        }
    }

    return new Response(decodeResultBody(result), {
        status: (result && result.statusCode) || 200,
        headers,
    });
}

function sendNodeResponse(response, result) {
    const headers = {};
    const raw = (result && result.headers) || {};

    for (const [key, value] of Object.entries(raw)) {
        if (value != null) {
            headers[key] = value;
        }
    }

    response.writeHead((result && result.statusCode) || 200, headers);
    response.end(decodeResultBody(result));
}

async function launcher(input, response) {
    if (isNodeResponse(response)) {
        const result = await orig.launcher(await eventFromNodeRequest(input));
        sendNodeResponse(response, result);

        return;
    }

    if (isWebRequest(input)) {
        const result = await orig.launcher(await eventFromWebRequest(input));

        return responseFromResult(result);
    }

    const body = await readBody(input && input.body, input && input.isBase64Encoded);
    const headers = withoutHopHeaders((input && input.headers) || {}, body);
    const hostHeader = headerValue(headers, 'x-forwarded-host') || headerValue(headers, 'host');

    return orig.launcher({
        ...(input || {}),
        body: body.length ? body : undefined,
        headers,
        host: (input && input.host) || hostHeader,
    });
}

module.exports = launcher;
module.exports.launcher = launcher;
