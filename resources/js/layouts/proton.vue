<script setup>  
    import protonMenu from "../components/MenuComponent.vue";  
    const showSuspenseOverlay = true;
</script>

<template>
    <v-layout class="rounded rounded-md">
        <v-app-bar color="surface-variant" title="Proton">
            <protonMenu />
        </v-app-bar>
        <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
            <v-container>
                <v-row>
                    <v-col>
                        <RouterView v-slot="{ Component }">
                            <template v-if="Component">
                                <Suspense timeout="0">
                                    <component :is="Component"/>
                                    <template #fallback>
                                        <v-overlay
                                            :model-value="showSuspenseOverlay"
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
            </v-container>
        </v-main>
    </v-layout>
</template>
