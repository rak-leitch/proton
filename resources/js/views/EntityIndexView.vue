<script setup>
    import protonList from "../components/ListComponent.vue";
    import { request } from "../utilities/request";
    import { watch, ref, computed, toRefs } from "vue";
    
    const configData = ref({});
    const listSettings = ref({});
    const currentError = ref("");
    
    const props = defineProps({
        entityCode: String,
    });
    
    const { entityCode } = toRefs(props);
    
    watch(entityCode, async () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            const { json } = await request("config/view/entity-index", [
                entityCode.value,
            ]);
            configData.value = json;
            listSettings.value = {
                entityCode: configData.value.entity_code,
                contextCode: null,
                conectId: null
            };
        } catch (error) {
            currentError.value = error.message;
        }
    }
    
    const displayList = computed(() => {
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
            <v-alert
                v-if="currentError"
                type="error"
                title="Error"
            >
                {{ currentError }}
            </v-alert>
            <protonList 
                v-if="displayList"
                :settings="listSettings"
            />
        </template>
    </v-card>
</template>
