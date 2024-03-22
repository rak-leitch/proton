<script setup>
    import { ref, watch, toRefs } from "vue";
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
            const { json } = await request("config/display", [
                settings.value.entityCode,
                settings.value.entityId,
            ]);
            configData.value = json;
        } catch (error) {
            currentError.value = `Failed to set up display: ${error.message}`;
        }
    }
    
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
      <v-table>
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
                    <td v-if="field.frontend_type==='text'">{{ field.value }}</td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>
