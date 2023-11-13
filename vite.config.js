import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { exec } from "node:child_process";

export default defineConfig(({ command }) => ({
    plugins: [
        vue(),
        laravel({
            buildDirectory: 'adepta/proton/assets',
            input: ['resources/js/proton.js'],
        }), {   
            name: 'publish-test-assets',
            closeBundle(options) {
                if (command === 'build') {
                    //Republish the assets into the testbench Laravel installation
                    exec('vendor/bin/testbench-dusk vendor:publish --force --tag=assets');
                }
            }
        }
    ]
}));
