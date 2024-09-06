<script setup lang="ts">
    import ProtonForm from "../components/FormComponent.vue";
    import { request } from "../utilities/request";
    import { ref } from "vue";
    import { useRoute } from "vue-router";
    import { RequestParams, FormComponentSettings } from "../types";
    
    type ConfigData = {
        entityCode: string; 
        title: string;
    };
    
    const props = defineProps<{
      entityCode: string
    }>();
    
    const configData = ref<ConfigData>({
        entityCode: '',
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
    const route = useRoute();
    const initialised = ref(false);
    
    async function getConfig() {
        try {
        
            const params: RequestParams = [
                props.entityCode,
            ];
            
            const { response } = await request<ConfigData>({
                path: "config/view/entity-create", 
                params: params,
            });
            
            configData.value = response;
            formSettings.value.entityCode = configData.value.entityCode;
            formSettings.value.configPath = "config/form-create";
            formSettings.value.submitPath = "submit/form-create";

            if((typeof route.query.contextCode === 'string') && (typeof route.query.contextId === 'string')) {
                formSettings.value.contextCode = route.query.contextCode;
                formSettings.value.contextId = route.query.contextId;
            }
            initialised.value = true;
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to get config: ${error.message}`;
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
