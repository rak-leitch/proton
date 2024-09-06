<script setup lang="ts">
    import ProtonList from "../components/ListComponent.vue";
    import { request } from "../utilities/request";
    import { watch, ref, toRefs } from "vue";
    import { RequestParams, ListComponentSettings } from "../types";
    
    type ConfigData = {
        entityCode: string; 
        title: string;
    };
    
    const props = defineProps<{
        entityCode: string;
    }>();
    
    const configData = ref<ConfigData>({
        entityCode: "",
        title: "",
    });
    
    const listSettings = ref<ListComponentSettings>({
        entityCode: "",
        contextCode: "",
        contextId: "",
    });
    
    const currentError = ref("");
    const initialised = ref(false);
    const { entityCode } = toRefs(props);
    
    watch(entityCode, async () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            
            const params: RequestParams = [
                entityCode.value,
            ];
            
            const { response } = await request<ConfigData>({
                path: "config/view/entity-index", 
                params: params,
            });
            configData.value = response;
            listSettings.value = {
                entityCode: configData.value.entityCode,
                contextCode: "",
                contextId: ""
            };
            initialised.value = true;
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = error.message;
            }
        }
    }
    
    // @ts-ignore
    await getConfig();

</script>

<template>
    <v-card class="my-4" elevation="4">
        <template 
            v-slot:title
            v-if="initialised"
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
                v-if="initialised"
                :settings="listSettings"
            />
        </template>
    </v-card>
</template>
