if (process.env.webpackconfig) {
    require(`${__dirname}/webpack.${process.env.webpackconfig}.mix.js`);
}
