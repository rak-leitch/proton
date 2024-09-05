<script setup lang="ts">
    import { ref, watch, toRefs } from "vue";
    import { HttpMethod, request } from "../utilities/request";
    import { useRouter } from "vue-router";
    import { RequestParams, RequestQueryParams, ListComponentSettings } from "../types";
    
    type ListItem = {
        [key: string]: any;
    };
    
    type ConfigField = {
        title: string;
        key: string;     
        sortable: boolean;
    };
    
    type ConfigPageSizeOption = {
        value: number;
        title: string;
    };
    
    type ConfigData = {
        fields: Array<ConfigField>;
        primaryKey: string;
        canCreate: boolean; 
        initialPageSize: number;
        entityLabel: string;
        version: string;
        pageSizeOptions: Array<ConfigPageSizeOption>;
    };
    
    type RowPermissions = {
        [key: number]: {
            update: boolean;
            view: boolean;
            delete: boolean;
        };
    };
    
    type SortBy = {
        key: string;
        order: number;
    };
    
    type CurrentListOptions = {
        page: number;
        itemsPerPage: number;
        sortBy: Array<SortBy>;
    };
    
    const props = defineProps<{
      settings: ListComponentSettings
    }>();

    const configData = ref<ConfigData>({
        fields: [],
        primaryKey: "",
        canCreate: false, 
        initialPageSize: 0,
        entityLabel: "",
        version: "",
        pageSizeOptions: [],
    });
    
    const router = useRouter();
    const serverItems = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const currentError = ref("");
    const configVersion = ref("");
    let rowPermissions: RowPermissions = {};
    const showDeleteConfirmation = ref(false);
    let pendingDeleteKey = -1;
    const initialised = ref(false);
    const { settings } = toRefs(props);
    
    let currentListOptions: CurrentListOptions = {
        page: 1,
        itemsPerPage: 5, 
        sortBy: [],
    };
    
    watch(settings, () => {
        getConfig();
    });
    
    async function getConfig() {
        try {
            currentError.value = "";
            
            const params: RequestParams = [
                settings.value.entityCode,
            ];
             
            const { json } = await request({
                path: "config/list",
                params: params,
            });
            
            configData.value = json;
            initialised.value = true;
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to set up list: ${error.message}`;
            }
        }
    }
    
    async function loadData ({ 
        page, 
        itemsPerPage, 
        sortBy 
    } : CurrentListOptions) {
        try {
            loading.value = true;
            
            const queryParams: RequestQueryParams = {};
            
            currentListOptions = { page, itemsPerPage, sortBy };
            
            if(sortBy.length) {
                const [sort] = sortBy;
                queryParams.sortField = String(sort.key);
                queryParams.sortOrder = String(sort.order);
            }
            
            if(settings.value.contextCode && settings.value.contextId) {
                queryParams.contextCode = settings.value.contextCode;
                queryParams.contextId = settings.value.contextId;
            }
            
            const params: RequestParams = [
                settings.value.entityCode,
                page,
                itemsPerPage
            ]; 
            
            const { json } = await request({
                path: "data/list", 
                params: params, 
                queryParams
            });
            
            serverItems.value = json.data;
            totalItems.value = json.totalRows;
            rowPermissions = json.permissions;
        } catch (error) {
            serverItems.value = [];
            totalItems.value = 0;
            rowPermissions = [];
            if (error instanceof Error) {
                currentError.value = `Failed to get list data: ${error.message}`;
            }
        } finally {
            loading.value = false;
        }
    }
    
    function updateItem(item: ListItem) {
        pushRoute("entity-update", item);
    }
    
    function viewItem(item: ListItem) {
        pushRoute("entity-view", item);
    }
    
    function deleteItem(item: ListItem) {
        if(configData.value.primaryKey) {
            pendingDeleteKey = item[configData.value.primaryKey];
            showDeleteConfirmation.value = true;
        }
    }
    
    function closeDeleteDialog() {
        showDeleteConfirmation.value = false;
        pendingDeleteKey = -1;
    }
    
    async function confirmDelete() {
        try {
            const params: RequestParams = [
                settings.value.entityCode,
                pendingDeleteKey,
            ]; 
            
            await request({
                path: "delete/list", 
                params: params,
                method: HttpMethod.Delete,
            });
            loadData(currentListOptions);
        } catch (error) {
            if (error instanceof Error) {
                currentError.value = `Failed to delete item: ${error.message}`;
            }
        } finally {
            closeDeleteDialog();
        }
    }
    
    function pushRoute(type: string, item: ListItem) {
        if(configData.value.primaryKey) {
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
    }
    
    function pushCreateRoute() {
        const route = {
            name: 'entity-create',
            params: { 
                entityCode: settings.value.entityCode,
            },
            query: {},
        };
        
        if(settings.value.contextCode && settings.value.contextId) {
            route['query'] = { 
                contextCode: settings.value.contextCode,
                contextId: settings.value.contextId,
            };
        }
        
        router.push(route);
    }
    
    // @ts-ignore
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
            v-if="initialised"
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
