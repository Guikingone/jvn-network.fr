/**
 * Created by PhpStorm.
 * User: Guillaume Loulier | guillaume.loulier[at]hotmail.fr
 */

var webpack = require('webpack');
var path = require("path");

var DEV_DIR = path.resolve(__dirname, "web/dev/");
var PROD_DIR = path.resolve(__dirname, "web/prod/");

var SASS_DIR = path.resolve(__dirname, "web/dev/Sass");

var config = {
    entry: {
        'index': DEV_DIR + "/main.ts",
        'sass': SASS_DIR + "/Core/main.scss"
    },
    output: {
        path: PROD_DIR + "/app",
        filename: "[name].js",
        publicPath: "./web/prod"
    },
    module: {
        loaders: [
            { test: /\.ts$/, loader: 'ts' },
            { test: /\.scss$/, loaders: ["style", "css", "sass"] }
        ]
    },
    resolve: {
        extensions: ["", ".webpack.js", ".web.js", ".ts", ".js"]
    }
};

module.exports = config;
