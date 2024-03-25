<script setup>
    import protonDisplay from "../components/DisplayComponent.vue";
    import protonList from "../components/ListComponent.vue";
    import { request } from "../utilities/request";
    import { watch, ref, computed, toRefs } from "vue";
    
    const configData = ref({});
    const currentError = ref("");
    
    const props = defineProps({
        entityCode: String,
        entityId: String
    });
    
    const { entityCode, entityId } = toRefs(props);

    watch([entityCode, entityId], async () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            const { json } = await request({
                path: "config/view/entity-display", 
                params: [
                    entityCode.value,
                    entityId.value,
                ]
            });
            configData.value = json;
        } catch (error) {
            currentError.value = error.message;
        }
    }
    
    const displayComponents = computed(() => {
        return (Object.keys(configData.value).length && !currentError.value);
    });
    
    await getConfig();

</script>

<template>
    <v-alert
        v-if="currentError"
        type="error"
        title="Error"
    >
        {{ currentError }}
    </v-alert>
    <v-card v-if="displayComponents" class="my-4" elevation="4" >
        <template v-slot:title>
            {{ configData.title }}
        </template>
        <template v-slot:text>
            <protonDisplay
                :settings="configData.displaySettings"
            />
        </template>
    </v-card>
    <v-card v-if="displayComponents" class="my-4" elevation="4" v-for="(listConfig) in configData.lists">
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
