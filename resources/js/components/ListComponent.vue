<script setup>
    import { ref, watch, toRefs, computed } from "vue";
    import { HttpMethod, request } from "../utilities/request";
    import { useRouter } from "vue-router";

    const configData = ref({});
    const router = useRouter();
    const serverItems = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const currentError = ref("");
    const configVersion = ref("");
    let rowPermissions = {};
    const showDeleteConfirmation = ref(false);
    let pendingDeleteKey = -1;
    let currentListOptions = {};
   
    const props = defineProps({
        settings: Object,
    });
    
    const { settings } = toRefs(props);
    
    watch(settings, () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            const { json } = await request({
                path: "config/list",
                params: [
                    settings.value.entityCode,
                ]
            });
            configData.value = json;
        } catch (error) {
            currentError.value = `Failed to set up list: ${error.message}`;
        }
    }
    
    async function loadData ({ page, itemsPerPage, sortBy }) {
        try {
            loading.value = true;
            const queryParams = {};
            
            currentListOptions = { page, itemsPerPage, sortBy };
            
            if(sortBy.length && sortBy[0]) {
                queryParams.sortField = sortBy[0].key;
                queryParams.sortOrder = sortBy[0].order;
            }
            
            if(settings.value.contextCode && settings.value.contextId) {
                queryParams.contextCode = settings.value.contextCode;
                queryParams.contextId = settings.value.contextId;
            }
            
            const { json } = await request({
                path: "data/list", 
                params: [
                    settings.value.entityCode,
                    page,
                    itemsPerPage
                ], 
                queryParams
            });
            
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
        pendingDeleteKey = item[configData.value.primaryKey];
        showDeleteConfirmation.value = true;
    }
    
    function closeDeleteDialog() {
        showDeleteConfirmation.value = false;
        pendingDeleteKey = -1;
    }
    
    async function confirmDelete() {
        try {
            await request({
                path: "delete/list", 
                params: [
                    settings.value.entityCode,
                    pendingDeleteKey,
                ],
                method: HttpMethod.Delete
            });
            
            if(Object.keys(currentListOptions).length > 0) {
                loadData(currentListOptions);
            }
        } catch (error) {
            currentError.value = `Failed to delete item: ${error.message}`;
        } finally {
            closeDeleteDialog();
        }
    }
    
    function pushRoute(type, item) {
        const primaryKeyValue = item[configData.value.primaryKey];
        const entityCode = settings.value.entityCode;
        router.push({ 
            name: type, 
            params: { 
                entityCode: entityCode,  
                entityId: primaryKeyValue
            }
        });
    }
    
    function pushCreateRoute() {
        const route = {
            name: 'entity-create',
            params: { 
                entityCode: settings.value.entityCode,
            }
        };
        
        if(settings.value.contextCode && settings.value.contextId) {
            route['query'] = { 
                contextCode: settings.value.contextCode,
                contextId: settings.value.contextId,
            };
        }
        
        router.push(route);
    }
    
    const displayList = computed(() => {
        return (Object.keys(configData.value).length);
    });
    
    await getConfig();

</script>

<template>
    <div
        :dusk="`list-${settings.entityCode}`"
    >
        <v-alert
            v-if="currentError"
            type="error"
            title="Error"
        >
            {{ currentError }}
        </v-alert>
        <v-data-table-server
            :items-per-page="configData.initialPageSize"
            :headers="configData.fields"
            :items-length="totalItems"
            :items="serverItems"
            :loading="loading"
            @update:options="loadData"
            :items-per-page-options="configData.pageSizeOptions"
            :key="configData.version"
            v-if="displayList"
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                > 
                    <v-spacer></v-spacer>
                    <v-btn
                        v-if="configData.canCreate"
                        color="primary"
                        @click="pushCreateRoute"
                        class="create-entity-button"
                    >
                        New {{ configData.entityLabel }}
                    </v-btn>
                    <v-dialog v-model="showDeleteConfirmation" max-width="500px">
                        <v-card>
                            <v-card-title 
                                class="text-h6 
                                text-center"
                            >
                                Are you sure you want to delete this item?
                            </v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn 
                                    color="blue-darken-1" 
                                    variant="text" 
                                    @click="closeDeleteDialog"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn 
                                    color="blue-darken-1" 
                                    variant="text" 
                                    @click="confirmDelete"
                                    class="delete-confirm"
                                >
                                    OK
                                </v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <div style="white-space: nowrap;">
                    <v-icon
                        v-if="rowPermissions[item.id].update"
                        icon="$pencil"
                        class="update-button me-2"
                        @click="updateItem(item)"
                    />
                    <v-icon
                        v-if="rowPermissions[item.id].view"
                        icon="$eye"
                        class="display-button me-2"
                        @click="viewItem(item)"
                    />
                    <v-icon
                        v-if="rowPermissions[item.id].delete"
                        icon="$rubbish"
                        class="delete-button me-2"
                        @click="deleteItem(item)"
                    />
                </div>
            </template>
        </v-data-table-server>
    </div>
</template>
