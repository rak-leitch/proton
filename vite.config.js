import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { exec } from "node:child_process";
import vuetify from 'vite-plugin-vuetify';

export default defineConfig(({ command }) => ({
    plugins: [
        vue(),
        laravel({
            publicDirectory: 'build',
            buildDirectory: 'artefacts',
            input: ['resources/js/proton.js'],
        }), {   
            name: 'publish-test-assets',
            closeBundle(options) {
                if (command === 'build') {
                    //Publish the assets into the testbench Laravel installation
                    exec('vendor/bin/testbench-dusk vendor:publish --force --tag=assets');
                }
            }
        },
        vuetify(),
        //vuetify({ autoImport: true, styles: { configFile: 'resources/styles/vuetify.scss' } }),
    ]
}));
