'use strict';

const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const bridgeSource = fs.readFileSync(path.join(__dirname, 'bridge.js'));

function ensureDependencies() {
    if (fs.existsSync(path.join(__dirname, 'node_modules', 'vercel-php'))) {
        return;
    }

    const npm = process.platform === 'win32' ? 'npm.cmd' : 'npm';
    const result = spawnSync(npm, ['install', '--omit=dev', '--no-audit', '--no-fund'], {
        cwd: __dirname,
        stdio: 'inherit',
        env: process.env,
    });

    if (result.error) {
        throw result.error;
    }

    if (result.status !== 0) {
        throw new Error('Installing vercel-php for the Vercel build failed');
    }
}

function useSupportedNodeRuntime() {
    const indexPath = path.join(__dirname, 'node_modules', 'vercel-php', 'dist', 'index.js');
    const source = fs.readFileSync(indexPath, 'utf8');
    const needle = 'const nodeVersion = await (0, build_utils_1.getNodeVersion)(workPath);';
    const replacement = "const nodeVersion = { runtime: 'nodejs22.x' };";

    if (source.includes(replacement)) {
        return;
    }

    if (!source.includes(needle)) {
        throw new Error('vercel-php did not contain the expected Node.js version lookup');
    }

    fs.writeFileSync(indexPath, source.replace(needle, replacement));
}

function loadPhp() {
    ensureDependencies();
    // The PHP builder's Node helper only accepts Node 20 and older.
    // This project uses Node 24, so the function is pinned to Node 22.
    useSupportedNodeRuntime();

    return require('vercel-php');
}

exports.version = 3;

exports.shouldServe = function shouldServe(options) {
    return loadPhp().shouldServe(options);
};

exports.prepareCache = async function prepareCache(options) {
    return loadPhp().prepareCache(options);
};

exports.build = async function build(options) {
    const php = loadPhp();
    const { FileBlob } = require('@vercel/build-utils');
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
    delete lambda.awsLambdaHandler;
    lambda.supportsResponseStreaming = false;

    return result;
};
