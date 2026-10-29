'use strict';

process.env.LAMBDA_TASK_ROOT = process.env.LAMBDA_TASK_ROOT || '/var/task';

const orig = require('./launcher-orig.js');

function requestBody(event) {
    const body = event.body;

    if (body == null || body === '') {
        return undefined;
    }

    if (Buffer.isBuffer(body)) {
        return body;
    }

    if (Array.isArray(body)) {
        return Buffer.from(body);
    }

    if (typeof body === 'string') {
        return Buffer.from(body, event.isBase64Encoded ? 'base64' : 'utf8');
    }

    return undefined;
}

exports.launcher = async function launcher(event) {
    const headers = event.headers || {};
    const next = {
        ...event,
        body: requestBody(event),
        host: event.host || headers['x-forwarded-host'] || headers.host,
    };

    return orig.launcher(next);
};
