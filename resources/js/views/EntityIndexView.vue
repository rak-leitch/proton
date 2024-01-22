<script setup>
    import protonList from "../components/ListComponent.vue";
    import { useAjax } from "../composables/ajax";
    import { useRoute } from "vue-router";
    import { watch, ref } from "vue";
    
    const configData = ref({});
    const listSettings = ref({});
    const route = useRoute();
    const viewType = route.name;
    const currentError = ref("");
    const displayList = ref(false);

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
                viewType: viewType,
                entityCode: route.params.entityCode
            };
            
            configData.value = await useAjax("config/view", getParams);
            
            listSettings.value = {
                entityCode: route.params.entityCode,
                viewType: viewType
            };
            
            displayList.value = true;
        } catch (error) {
            displayList.value = false;
            currentError.value = error.message;
        }
    }
    
    await getConfig();

</script>

<template>
    <v-card class="my-4" elevation="4">
        <template v-slot:title>
            {{ configData.entity_label_plural }}
        </template>
        <template v-slot:text>
            <v-alert
                v-if="currentError"
                type="error"
                title="Error"
            >
                {{ currentError }}
            </v-alert>
            <protonList 
                v-if="displayList"
                :settings="listSettings"
            />
        </template>
    </v-card>
</template>
