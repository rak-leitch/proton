<script setup>
    import protonForm from "../components/FormComponent.vue";
    import { useAjax } from "../composables/ajax";
    import { useRoute } from "vue-router";
    import { watch, ref } from "vue";
    
    const configData = ref({});
    const formSettings = ref({});
    const route = useRoute();
    const currentError = ref("");

    watch(
        () => route.params,
        () => {
            currentError.value = "";
            getConfig();
        }
    );
    
    async function getConfig() {
        try {
            
            const getParams = {
                entityCode: route.params.entityCode,
                entityId: route.params.entityId,
            };
            
            configData.value = await useAjax("config/view/entity-update", getParams);
            
            formSettings.value = {
                entityCode: configData.value.entity_code,
                entityId: configData.value.entity_id
            };
        } catch (error) {
            currentError.value = error.message;
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
