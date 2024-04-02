<script setup>
    import { ref, watch, toRefs, computed } from "vue";
    import { request } from "../utilities/request";

    const configData = ref({});
    const currentError = ref("");
   
    const props = defineProps({
        settings: Object,
    });
    
    const { settings } = toRefs(props);
    
    watch(settings, () => {
        getConfigData();
    });
    
    async function getConfigData() {
        try {
            currentError.value = "";
            const { json } = await request({
                path: "config/display", 
                params: [
                    settings.value.entityCode,
                    settings.value.entityId,
                ]
            });
            configData.value = json;
        } catch (error) {
            currentError.value = `Failed to set up display: ${error.message}`;
        }
    }
    
    const display = computed(() => {
        return (Object.keys(configData.value).length && !currentError.value);
    });
    
    await getConfigData();

</script>

<template>
    <div class="display-component">
        <v-alert
            v-if="currentError"
            type="error"
            title="Error"
        >
            {{ currentError }}
        </v-alert>
      <v-table
          v-if="display"
      >
            <thead>
                <tr>
                    <th class="text-left">Name</th>
                    <th class="text-left">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="field in configData.fields"
                    :key="field.name"
                >
                    <td>{{ field.title }}</td>
                    <td v-if="field.frontendType==='text'">{{ field.value }}</td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>
