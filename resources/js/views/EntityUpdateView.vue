<script setup>
    import protonForm from "../components/FormComponent.vue";
    import { request } from "../utilities/request";
    import { ref, computed } from "vue";
    
    const configData = ref({});
    const formSettings = ref({});
    const currentError = ref("");
    
    const props = defineProps({
        entityCode: String,
        entityId: String
    });
    
    async function getConfig() {
        try {
            const { json } = await request({
                path: "config/view/entity-update", 
                params: [
                    props.entityCode,
                    props.entityId,
                ]
            });
            configData.value = json;
            formSettings.value = {
                entityCode: configData.value.entityCode,
                entityId: configData.value.entityId,
                configPath: "config/form-update",
                submitPath: "submit/form-update"
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
            <protonForm
                v-if="display"
                :settings="formSettings"
            />
        </template>
    </v-card>
</template>
