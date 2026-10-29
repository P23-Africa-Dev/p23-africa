'use strict';

const fs = require('fs');
const path = require('path');
const php = require('vercel-php');
const { FileBlob } = require('@vercel/build-utils');

const bridgeSource = fs.readFileSync(path.join(__dirname, 'bridge.js'));

exports.version = php.version;
exports.shouldServe = php.shouldServe;
exports.prepareCache = php.prepareCache;

exports.build = async function build(options) {
    const result = await php.build(options);
    const lambda = result && result.output;

    if (!lambda || !lambda.files || !lambda.files['launcher.js']) {
        throw new Error('vercel-php did not produce launcher.js');
    }

    lambda.files['launcher-orig.js'] = lambda.files['launcher.js'];
    lambda.files['launcher.js'] = new FileBlob({ data: bridgeSource });

    // The current Vercel bootstrap imports this handler as a file path.
    // vercel-php still reports the AWS name launcher.launcher, which does not exist.
    lambda.handler = 'launcher.js';
    lambda.launcherType = 'Nodejs';
    lambda.awsLambdaHandler = 'launcher.launcher';
    lambda.supportsResponseStreaming = false;

    return result;
};
