<script setup>
    import { ref } from "vue";
    import { request } from "../utilities/request";
    import { useRouter } from "vue-router";

    const configData = ref({});
    const formData = ref({});
    const router = useRouter();
    const currentError = ref("");
    const submitInProgress = ref(false);
    const errorMessages = ref({});
    const successStatus = 200;
   
    const props = defineProps({
        settings: Object,
    });
    
    async function getConfig() {
        try {
            const { json } = await request(props.settings.configPath, getRequestParams());
            configData.value = json.config;
            formData.value = json.data;
        } catch (error) {
            currentError.value = `Failed to get form config: ${error.message}`;
        }
    }
    
    async function submitForm() {
        try {
            submitInProgress.value = true;
            const { json, status } = await request(props.settings.submitPath, getRequestParams(), formData.value);
            errorMessages.value = json.errors ? json.errors : {};
            if(status === successStatus) {
                router.push(props.settings.successRoute);
            }
        } catch (error) {
            currentError.value = `Failed to submit form: ${error.message}`;
        } finally {
            submitInProgress.value = false;
        }
    }
    
    function getErrorMessage(fieldKey) {
        let errorMessage = null;
        if(errorMessages.value.hasOwnProperty(fieldKey)) {
            errorMessage = errorMessages.value[fieldKey].join(" ");
        }
        return errorMessage;
    };
    
    function getRequestParams() {
        const requestParams = [
            props.settings.entityCode,
        ];
        if(props.settings.entityId) {
            requestParams.push(props.settings.entityId);
        }
        return requestParams;
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
    <v-form @submit.prevent="submitForm">
        <template v-for="(field) in configData.fields">
            <v-text-field v-if="field.frontend_type='text'"
                v-model="formData[field.key]"
                :error-messages="getErrorMessage(field.key)"
            >
                <template v-slot:label>
                    <span>
                        {{ field.title }} 
                        <span v-if="field.required" class="text-red">*</span>
                    </span>
                </template>
            </v-text-field>
        </template>
        <v-btn
            :loading="submitInProgress"
            type="submit"
            block
            class="mt-2"
            text="Submit"
        ></v-btn>
    </v-form>
</template>
