#!/usr/bin/env node
const { argv } = require('yargs');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const postcssImport = require('postcss-import');
const postcssNested = require('postcss-nested');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const path = require('path');

const options = {
    entry: {
        main: path.resolve(process.cwd(), argv.main)
    },
    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract({
                    fallbackLoader: "style-loader",
                    loader: "css-loader!postcss-loader"
                })
            }
            /*{
                test: /\.css$/,
                loader: "style-loader!css-loader!postcss-loader"
            }*/
        ]
    },
    output: {
        path: argv.out
    },
    plugins: [
        new ExtractTextPlugin("main.css")
    ],
    postcss: function(webpack) {
        return [
            postcssImport({
                root: process.cwd(),
                addDependencyTo: webpack
            }),
            postcssNested
        ];
    }
};
const compiler = webpack(options);

compiler.run((err, stats) => {
    if (err) {
        console.error(err.stack || err);
        if (err.details) console.error(err.details);
        process.on("exit", function() {
            process.exit(1); // eslint-disable-line
        });
        return;
    }
    console.log(stats.toString());
});