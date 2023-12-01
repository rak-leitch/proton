<script setup>
    import { ref } from 'vue';
    
    const errorActive = ref(false);
    
    //Set the error - TODO: this needs to report the message.
    function configError() {
        errorActive.value = true;
    }
</script>

<template>
    <v-layout class="rounded rounded-md">
        <v-app-bar color="surface-variant" title="Proton"></v-app-bar>
        <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
            <v-container>
                <v-row>
                    <v-col>
                        <v-alert type="error" v-if="errorActive" title="Error" text="Something went wrong"></v-alert>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <router-view @config-error="configError" v-slot="{ Component }">
                            <component :is="Component"/>
                        </router-view>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="mt-3 text-center">
                            <router-link to="/proton/entity/project/index">Projects</router-link>
                            <router-link to="/proton/entity/task/index">Tasks</router-link>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-layout>
</template>
