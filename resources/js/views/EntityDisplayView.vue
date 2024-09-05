<script setup lang="ts">
    import ProtonDisplay from "../components/DisplayComponent.vue";
    import ProtonList from "../components/ListComponent.vue";
    import { request } from "../utilities/request";
    import { watch, ref, toRefs } from "vue";
    import { RequestParams, DisplayComponentSettings, ListComponentSettings } from "../types";
    
    type ListSettings = {
        title: string;
        listSettings: ListComponentSettings;
    };
    
    type ConfigData = {
        title: string;
        lists: Array<ListSettings>;
        displaySettings: DisplayComponentSettings;
    };
    
    const props = defineProps<{
        entityCode: string;
        entityId: string;
    }>();
    
    const configData = ref<ConfigData>({
        title: "",
        lists: [],
        displaySettings: {
            entityCode: "",
            entityId: "",
        },
    });
    
    const currentError = ref("");
    const { entityCode, entityId } = toRefs(props);
    const initialised = ref(false);
    
    watch([entityCode, entityId], async () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            
            const params: RequestParams = [
                entityCode.value,
                entityId.value,
            ]; 
            
            const { json } = await request({
                path: "config/view/entity-display", 
                params: params,
            });
            configData.value = json;
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
    <v-alert
        v-if="currentError"
        type="error"
        title="Error"
    >
        {{ currentError }}
    </v-alert>
    <v-card v-if="initialised" class="my-4" elevation="4" >
        <template v-slot:title>
            {{ configData.title }}
        </template>
        <template v-slot:text>
            <ProtonDisplay
                :settings="configData.displaySettings"
            />
        </template>
    </v-card>
    <v-card v-if="initialised" class="my-4" elevation="4" v-for="(listConfig) in configData.lists">
        <template v-slot:title>
            {{ listConfig.title }}
        </template>
        <template v-slot:text>
            <ProtonList 
                :settings="listConfig.listSettings"
            />
        </template>
    </v-card>
</template>
