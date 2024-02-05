<script setup>
    import { ref, watch } from "vue";
    import { useAjax } from "../composables/ajax";
    import { useRouter } from "vue-router";

    const configData = ref({});
    const formData = ref({});
    const router = useRouter();
    const currentError = ref("");
    const submitInProgress = ref(false);
    const errorMessages = ref({});
    const validationFailedStatus = 422;
    const successStatus = 200;
    const unreportableStatuses = [validationFailedStatus];
   
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
                    
            const requestParams = [
                props.settings.entityCode,
            ];
            
            if(props.settings.entityId) {
                requestParams.push(props.settings.entityId);
            }
            
            const response = await useAjax(props.settings.configPath, requestParams);
            configData.value = response.body.config;
            formData.value = response.body.data;
            
        } catch (error) {
            currentError.value = `Failed to get form config: ${error.message}`;
        }
    }
    
    function getErrorMessage(fieldKey) {
        let errorMessage = null;
        if(errorMessages.value.hasOwnProperty(fieldKey)) {
            errorMessage = errorMessages.value[fieldKey].join(" ");
        }
        return errorMessage;
    };
    
    async function submitForm() {
        try {
            
            submitInProgress.value = true;
            
            const requestParams = [
                props.settings.entityCode,
            ];
            
            if(props.settings.entityId) {
                requestParams.push(props.settings.entityId);
            }
            
            const response = await useAjax(props.settings.submitPath, requestParams, formData.value, "POST", unreportableStatuses);
            errorMessages.value = response.body.errors ? response.body.errors : {};
            
            if(response.statusCode === successStatus) {
                router.push(props.settings.successRoute);
            }
        } catch (error) {
            currentError.value = `Failed to submit form: ${error.message}`;
        } finally {
            submitInProgress.value = false;
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
    <v-form @submit.prevent="submitForm">
        <v-text-field v-for="(field) in configData.fields"
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
        <v-btn
            :loading="submitInProgress"
            type="submit"
            block
            class="mt-2"
            text="Submit"
        ></v-btn>
    </v-form>
</template>
