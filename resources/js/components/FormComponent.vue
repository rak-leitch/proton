<script setup lang="ts">
    import { ref } from "vue";
    import { request } from "../utilities/request";
    import { useRouter } from "vue-router";
    import { RequestParams, FormComponentSettings } from "../types";
    
    type ErrorMessages = {
        [key: string]: Array<string>;
    };
    
    type ConfigSelectOption = {
        value: any;
        title: string|number;
    };
    
    type ConfigField = {
        title: string, 
        key: string, 
        relatedEntityCode: string, 
        frontendType: string, 
        required: boolean, 
        selectOptions: Array<ConfigSelectOption>
    };
    
    type ConfigData = {
        fields: Array<ConfigField>;
    };
    
    type FormData = {
        [key: string]: any;
    };
    
    type ConfigResponse = {
        config: ConfigData;
        data: FormData;
    };
    
    type SubmitResponse = {
        message?: string;
        errors?: ErrorMessages
    };
    
    const props = defineProps<{
      settings: FormComponentSettings
    }>();

    const configData = ref<ConfigData>({
        fields: [],
    });
    
    const formData = ref<FormData>({});
    const router = useRouter();
    const currentError = ref("");
    const submitInProgress = ref(false);
    const errorMessages = ref<ErrorMessages>({});
    const successStatus = 200;
    const initialised = ref(false);
    
    async function getConfig() {
        try {
            const { response } = await request<ConfigResponse>({
                path: props.settings.configPath, 
                params: getRequestParams()
            });
            configData.value = response.config;
            formData.value = response.data;
            initialised.value = true;
            selectRelatedField();
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to setup form: ${error.message}`;
            }
        }
    }
    
    async function submitForm() {
        try {
            submitInProgress.value = true;
            const { response, status } = await request<SubmitResponse>({
                path: props.settings.submitPath, 
                method: props.settings.submitVerb,
                params: getRequestParams(), 
                bodyData: formData.value,
                acceptableErrors: [ 422 ],
            });
            errorMessages.value = response.errors ? response.errors : {};
            if(status === successStatus) {
                router.go(-1);
            }
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to submit form: ${error.message}`;
            }
        } finally {
            submitInProgress.value = false;
        }
    }
    
    function getErrorMessage(fieldKey: string): string {
        let errorMessage = "";
        if(Object.hasOwn(errorMessages.value, fieldKey)) {
            let fieldMessages = errorMessages.value[fieldKey];
            errorMessage = fieldMessages.join(" ");
        }
        return errorMessage;
    };
    
    function getRequestParams(): RequestParams {
        const requestParams: RequestParams = [
            props.settings.entityCode,
        ];
        if(props.settings.entityId) {
            requestParams.push(props.settings.entityId);
        }
        return requestParams;
    }
    
    function selectRelatedField() {
        const contextCode = props.settings.contextCode;
        const contextId = props.settings.contextId;
        
        if(contextCode && contextId) {
            const contextField = configData.value.fields.find(
                field => field.relatedEntityCode === contextCode
            );
            if(contextField) {
                const selectOption = contextField.selectOptions.find(
                    //Loose comparison as key type may not match
                    option => option.value == contextId
                );
                if(selectOption) {
                    formData.value[contextField.key] = selectOption.value;
                }
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
    <v-form 
        @submit.prevent="submitForm"
        v-if="initialised"
    >
        <template v-for="(field) in configData.fields">
            <v-text-field 
                v-if="field.frontendType==='text'"
                v-model="formData[field.key]"
                :error-messages="getErrorMessage(field.key)"
                :class="`field-${field.key}`"
            >
                <template v-slot:label>
                    <span>
                        {{ field.title }} 
                        <span v-if="field.required" class="text-red">*</span>
                    </span>
                </template>
            </v-text-field>
            <v-textarea 
                v-if="field.frontendType==='textarea'"
                v-model="formData[field.key]"
                :error-messages="getErrorMessage(field.key)"
                :class="`field-${field.key}`"
            >
                <template v-slot:label>
                    <span>
                        {{ field.title }} 
                        <span v-if="field.required" class="text-red">*</span>
                    </span>
                </template>
            </v-textarea>
            <v-select 
                v-if="field.frontendType==='select'"
                v-model="formData[field.key]"
                :error-messages="getErrorMessage(field.key)"
                :class="`field-${field.key}`"
                :items="field.selectOptions"
            >
                <template v-slot:label>
                    <span>
                        {{ field.title }} 
                        <span v-if="field.required" class="text-red">*</span>
                    </span>
                </template>
            </v-select>
        </template>
        <v-btn
            :loading="submitInProgress"
            type="submit"
            block
            class="mt-2 form-submit"
            text="Submit"
        ></v-btn>
    </v-form>
</template>
