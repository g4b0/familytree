const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass(['app.scss', 'familytree.scss'])
            .webpack('app.js')
            .combine([
                'resources/assets/js/plugins/cytoscape.min.js',
                'resources/assets/js/plugins/dagre.min.js',
                'resources/assets/js/plugins/cytoscape-dagre.js'
            ], 'public/js/plugins/graph.js')
            .scripts('familytree.js');
});
