<script setup>
    import { ref, watch } from "vue";
    import { request } from "../utilities/request";
    import { useRouter } from "vue-router";

    const configData = ref({});
    const router = useRouter();
    const itemsPerPage = ref(5);
    const serverItems = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const currentError = ref("");
    const configVersion = ref("");
    let rowPermissions = {};
   
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
            const { json } = await request("config/list", [
                props.settings.entityCode,
            ]);
            configData.value = json;
        } catch (error) {
            currentError.value = `Failed to get list config: ${error.message}`;
        }
    }
    
    async function loadData ({ page, itemsPerPage, sortBy }) {
        try {
            loading.value = true;
            const queryParams = {};
            
            if(sortBy.length) {
                queryParams.sortField = sortBy[0].key;
                queryParams.sortOrder = sortBy[0].order;
            }
            
            if(props.settings.contextCode && props.settings.contextId) {
                queryParams.contextCode = props.settings.contextCode;
                queryParams.contextId = props.settings.contextId;
            }
            
            const { json } = await request("data/list", [
                props.settings.entityCode,
                page,
                itemsPerPage
            ], queryParams, {});
            
            serverItems.value = json.data;
            totalItems.value = json.totalRows;
            rowPermissions = json.permissions;
        } catch (error) {
            serverItems.value = [];
            totalItems.value = 0;
            rowPermissions = [];
            currentError.value = `Failed to get list data: ${error.message}`;
        } finally {
            loading.value = false;
        }
    }
    
    function updateItem(item) {
        pushRoute("entity-update", item);
    }
    
    function viewItem(item) {
        pushRoute("entity-view", item);
    }
    
    function deleteItem(item) {
        console.log(item);
    }
    
    function pushRoute(type, item) {
        const primaryKeyValue = item[configData.value.primary_key];
        const entityCode = props.settings.entityCode;
        router.push({ 
            name: type, 
            params: { 
                entityCode: entityCode,  
                entityId: primaryKeyValue
            }
        });
    }
    
    function goToCreate() {
        router.push({
            name: 'entity-create',
            params: { 
                entityCode: props.settings.entityCode,
            }
        });
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
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="configData.fields"
        :items-length="totalItems"
        :items="serverItems"
        :loading="loading"
        item-value="name"
        @update:options="loadData"
        :items-per-page-options="configData.page_size_options"
        :key="configData.version"
    >
        <template v-slot:top>
            <v-toolbar
                flat
            > 
                <v-spacer></v-spacer>
                <v-btn
                    v-if="configData.can_create"
                    color="primary"
                    @click="goToCreate"
                    dusk="create-entity-button"
                >
                    New {{ configData.entity_label }}
                </v-btn>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                v-if="rowPermissions[item.id].update"
                icon="$pencil"
                class="me-2"
                @click="updateItem(item)"
                v-bind:dusk="`update-${item.id}`"
            />
            <v-icon
                v-if="rowPermissions[item.id].view"
                icon="$eye"
                class="me-2"
                @click="viewItem(item)"
                v-bind:dusk="`view-${item.id}`"
            />
            <v-icon
                v-if="rowPermissions[item.id].delete"
                icon="$rubbish"
                class="me-2"
                @click="deleteItem(item)"
                v-bind:dusk="`delete-${item.id}`"
            />
        </template>
    </v-data-table-server>
</template>
