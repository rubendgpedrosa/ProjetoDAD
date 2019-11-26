const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    entry: {
        ignition: './resources/js/app.js',
    },

    output: {
        path: `${__dirname}/resources/compiled`,
        publicPath: '/',
        filename: '[name].js',
    },

    module: {
        rules: [
            module.exports = {
                rules: [
                    {
                        test: /\.s(c|a)ss$/,
                        use: [
                            'vue-style-loader',
                            'css-loader',
                            {
                                loader: 'sass-loader',
                                // Requires sass-loader@^7.0.0
                                options: {
                                    implementation: require('sass'),
                                    fiber: require('fibers'),
                                    indentedSyntax: true // optional
                                },
                                // Requires sass-loader@^8.0.0
                                options: {
                                    implementation: require('sass'),
                                    sassOptions: {
                                        fiber: require('fibers'),
                                        indentedSyntax: true // optional
                                    },
                                },
                            },
                        ],
                    },
                ],
            },
            {
                test: /\.(js|tsx?)$/,
                use: 'babel-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
            {
                test: /\.css$/,
                use: [
                    'style-loader',
                    { loader: 'css-loader', options: { url: false } },
                    'postcss-loader',
                ],
            },
        ],
    },

    plugins: [new VueLoaderPlugin()],

    resolve: {
        extensions: ['.css', '.js', '.ts', '.vue'],
        alias: {
            vue$: 'vue/dist/vue.esm.js',
        },
    },

    stats: 'minimal',

    performance: {
        hints: false,
    },
};
