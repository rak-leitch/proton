<script setup>
    import ProtonList from "../components/ListComponent.vue";
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
            const { json } = await request({
                path: "config/view/entity-index", 
                params: [
                    entityCode.value,
                ]
            });
            configData.value = json;
            listSettings.value = {
                entityCode: configData.value.entityCode,
                contextCode: null,
                conectId: null
            };
        } catch (error) {
            currentError.value = error.message;
        }
    }
    
    const display = computed(() => {
        return (Object.keys(configData.value).length && !currentError.value);
    });
    
    await getConfig();

</script>

<template>
    <v-card class="my-4" elevation="4">
        <template 
            v-slot:title
            v-if="display"
        >
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
            <ProtonList 
                v-if="display"
                :settings="listSettings"
            />
        </template>
    </v-card>
</template>
