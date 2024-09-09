<script setup lang="ts">

    import { ref, watch, toRefs } from "vue";
    import { request } from "../utilities/request";
    import { RequestParams, DisplayComponentSettings } from "../types";
    
    type ConfigField = {
        title: string;
        key: string;
        frontendType: string;
        value: string|number;
    };
    
    type ConfigData = {
        fields: Array<ConfigField>;
    };
    
    const props = defineProps<{
      settings: DisplayComponentSettings
    }>();

    const configData = ref<ConfigData>({
        fields: [],
    });
    
    const currentError = ref("");
    const { settings } = toRefs(props);
    const initialised = ref(false);
    
    watch(settings, () => {
        getConfigData();
    });
    
    async function getConfigData() {
        try {
            currentError.value = "";
            const params: RequestParams = [
                settings.value.entityCode,
                settings.value.entityId,
            ]; 
            const { response } = await request<ConfigData>({
                path: "config/display", 
                params: params,
            });
            configData.value = response;
            initialised.value = true;
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to set up display: ${error.message}`;
            }
        }
    }
    
    // @ts-ignore
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
          v-if="initialised && !currentError"
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
                    :key="field.key"
                >
                    <td>{{ field.title }}</td>
                    <td v-if="field.frontendType==='text'">{{ field.value }}</td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>
