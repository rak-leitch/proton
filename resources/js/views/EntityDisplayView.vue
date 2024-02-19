<script setup>
    import protonDisplay from "../components/DisplayComponent.vue";
    import protonList from "../components/ListComponent.vue";
    import { request } from "../utilities/request";
    import { useRoute } from "vue-router";
    import { watch, ref, computed } from "vue";
    
    const configData = ref({});
    const route = useRoute();
    const currentError = ref("");

    watch(
        () => route.params,
        () => {
            getConfig();
        }
    );
    
    async function getConfig() {
        try {
            currentError.value = "";
            const { json } = await request("config/view/entity-display", [
                route.params.entityCode,
                route.params.entityId,
            ]);
            configData.value = json;
        } catch (error) {
            currentError.value = error.message;
        }
    }
    
    const displayLists = computed(() => {
        return (Object.keys(configData.value).length && !currentError.value);
    });
    
    await getConfig();

</script>

<template>
    <v-card class="my-4" elevation="4">
        <template v-slot:title>
            {{ configData.title }}
        </template>
        <template v-slot:text>
            <protonDisplay 
                :settings="configData.displaySettings"
            />
        </template>
    </v-card>
    <v-card class="my-4" elevation="4" v-if="displayLists" v-for="(listConfig) in configData.lists">
        <template v-slot:title>
            {{ listConfig.title }}
        </template>
        <template v-slot:text>
            <protonList 
                :settings="listConfig.listSettings"
            />
        </template>
    </v-card>
</template>
