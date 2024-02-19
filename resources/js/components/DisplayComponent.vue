<script setup>
    import { ref, watch } from "vue";
    import { request } from "../utilities/request";

    const configData = ref({});
    const currentError = ref("");
   
    const props = defineProps({
        settings: Object,
    });
    
    watch(
        () => props.settings,
        () => {
            getConfigData()
        }, {
            deep: true,
        }
    );
    
    async function getConfigData() {
        try {
            const { json } = await request("config/display", [
                props.settings.entityCode,
                props.settings.entityId,
            ]);
            configData.value = json;
        } catch (error) {
            currentError.value = `Failed to get display config: ${error.message}`;
        }
    }
    
    await getConfigData();

</script>

<template>
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
</template>
