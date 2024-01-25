<script setup>
    import { ref, watch } from "vue";
    import { useAjax } from "../composables/ajax";

    const configData = ref({});
    const formData = ref({});
    const currentError = ref("");
   
    const props = defineProps({
        settings: Object,
    });
    
    watch(
        () => props.settings,
        () => {
            getConfig()
        }, {
            deep: true,
        }
    );
    
    async function getConfig() {
        try {
            const getParams = {
                entityCode: props.settings.entityCode,
                entityId: props.settings.entityId
            };
            const response = await useAjax("config/form", getParams);
            configData.value = response.config;
            formData.value = response.data;
            
        } catch (error) {
            currentError.value = `Failed to get form config: ${error.message}`;
        }
    }
    
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
    <v-form @submit.prevent>
        <v-text-field v-for="(field) in configData.fields"
            v-model="formData[field.key]"
            :label="field.title"
        ></v-text-field>
        <v-btn type="submit" block class="mt-2">Submit</v-btn>
    </v-form>
</template>
