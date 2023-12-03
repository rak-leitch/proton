<script setup>
    import { ref } from "vue";
    
    const showOverlay = true;
    const reloadViewFlag = ref(false);
    
    function reloadView() {
        reloadViewFlag.value = !reloadViewFlag.value;
    }
</script>

<template>
    <v-layout class="rounded rounded-md">
        <v-app-bar color="surface-variant" title="Proton"></v-app-bar>
        <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
            <v-container>
                <v-row>
                    <v-col>
                        <RouterView v-slot="{ Component }">
                            <template v-if="Component">
                                <Suspense timeout="0">
                                    <component :is="Component" @reloadView="reloadView" :key="reloadViewFlag"/>
                                    <template #fallback>
                                        <v-overlay
                                            :model-value="showOverlay"
                                            class="align-center justify-center"
                                        >
                                            <v-progress-circular
                                                color="primary"
                                                indeterminate
                                                size="64"
                                             ></v-progress-circular>
                                        </v-overlay>
                                    </template>
                                </Suspense>
                            </template>
                        </RouterView>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="mt-3 text-center">
                            <router-link :to="{ name: 'entity-index', params: { entityCode: 'project' }}">Projects</router-link> |
                            <router-link :to="{ name: 'entity-index', params: { entityCode: 'task' }}">Tasks</router-link> | 
                            <router-link :to="{ name: 'entity-view', params: { entityCode: 'project' }}">Project View</router-link>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-layout>
</template>
