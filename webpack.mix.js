const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/scss/app.scss', 'public/css', { sassOptions: { quietDeps: true, silenceDeprecations: ['import','global-builtin','color-functions'] } })
   .options({ processCssUrls: false })
   .disableNotifications();
if (mix.inProduction()) mix.version();
mix.webpackConfig({ plugins: [{ apply(compiler) { compiler.hooks.afterCompile.tap('WatchViews', compilation => {
  compilation.contextDependencies.add(require('path').resolve(__dirname, 'resources/views'));
}); } }] });
