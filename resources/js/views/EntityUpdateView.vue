<script setup lang="ts">
    import ProtonForm from "../components/FormComponent.vue";
    import { request } from "../utilities/request";
    import { ref } from "vue";
    import { RequestParams, FormComponentSettings } from "../types";
    
    type ConfigData = {
        entityCode: string; 
        entityId: string;
        title: string;
    };
    
    const props = defineProps<{
      entityCode: string,
      entityId: string
    }>();
    
    const configData = ref<ConfigData>({
        entityCode: '',
        entityId: '',
        title: '',
    });
    
    const formSettings = ref<FormComponentSettings>({
        entityCode: "",
        entityId: null,
        configPath: "",
        submitPath: "",
        contextCode: "",
        contextId: "",
    });
    
    const currentError = ref("");
    const initialised = ref(false);
    
    async function getConfig() {
        try {
            
            const params: RequestParams = [
                props.entityCode,
                props.entityId,
            ];
            
            const { response } = await request<ConfigData>({
                path: "config/view/entity-update", 
                params: params,
            });
            
            configData.value = response;
            formSettings.value.entityCode = configData.value.entityCode;
            formSettings.value.entityId = configData.value.entityId;
            formSettings.value.configPath = "config/form-update";
            formSettings.value.submitPath = "submit/form-update";
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
            <ProtonForm
                v-if="initialised"
                :settings="formSettings"
            />
        </template>
    </v-card>
</template>
