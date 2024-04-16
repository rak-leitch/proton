<script setup>
    import ProtonForm from "../components/FormComponent.vue";
    import { request } from "../utilities/request";
    import { ref, computed } from "vue";
    import { useRoute } from "vue-router";
    
    const configData = ref({});
    const formSettings = ref({});
    const currentError = ref("");
    const route = useRoute();
    
    const props = defineProps({
        entityCode: String,
    });
    
    async function getConfig() {
        try {
            const { json } = await request({
                path: "config/view/entity-create", 
                params: [
                    props.entityCode,
                ]
            });
            configData.value = json;
            formSettings.value = {
                entityCode: configData.value.entityCode,
                configPath: "config/form-create",
                submitPath: "submit/form-create"
            };
            if(route.query.contextCode && route.query.contextId) {
                formSettings.value.contextCode = route.query.contextCode;
                formSettings.value.contextId = route.query.contextId;
            }
        } catch (error) {
            currentError.value = `Failed to get config: ${error.message}`;
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
            <ProtonForm
                v-if="display"
                :settings="formSettings"
            />
        </template>
    </v-card>
</template>
