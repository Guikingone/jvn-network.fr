/*
 * This file is part of the jvn-network project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@hotmail.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

var webpack = require('webpack');
var path = require("path");

var DEV_DIR = path.resolve(__dirname, "web/dev/");
var PROD_DIR = path.resolve(__dirname, "web/prod/");

var TS_DIR = path.resolve(DEV_DIR + "/Typescript");
var SASS_DIR = path.resolve(DEV_DIR + "/Sass");

var config = {
    entry: {
        'Angular': TS_DIR + "/Angular/main.ts",
        'sass': SASS_DIR + "/Core/main.scss"
    },
    output: {
        path: PROD_DIR + "/app",
        filename: "[name].js",
        publicPath: "./web/prod"
    },
    devtool: "source-map",
    module: {
        loaders: [
            { test: /\.ts$/, loader: 'ts' },
            { test: /\.scss$/, loaders: ["style", "css", "sass"] }
        ]
    },
    resolve: {
        extensions: ["", ".webpack.js", ".web.js", ".ts", ".js", ".css"]
    }
};

module.exports = config;
