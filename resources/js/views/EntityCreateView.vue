<script setup>
    import protonForm from "../components/FormComponent.vue";
    import { request } from "../utilities/request";
    import { useRoute } from "vue-router";
    import { ref } from "vue";
    
    const configData = ref({});
    const formSettings = ref({});
    const route = useRoute();
    const currentError = ref("");
    
    async function getConfig() {
        try {
            const { json } = await request("config/view/entity-create", [
                route.params.entityCode,
            ]);
            configData.value = json;
            formSettings.value = {
                entityCode: configData.value.entity_code,
                configPath: "config/form-create",
                submitPath: "submit/form-create",
                successRoute: {
                    name: 'entity-index',
                    params: { 
                        entityCode: configData.value.entity_code,
                    }  
                }
            };
        } catch (error) {
            currentError.value = `Failed to get config: ${error.message}`;
        }
    }
    
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
            <protonForm
                :settings="formSettings"
            />
        </template>
    </v-card>
</template>
