/**
 * Created by PhpStorm.
 * User: Guillaume Loulier | guillaume.loulier[at]hotmail.fr
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
