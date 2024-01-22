<script setup>
    import { ref, watch } from "vue";
    import { useAjax } from "../composables/ajax";

    const configData = ref({});
    const configChange = ref(false);
    const itemsPerPage = ref(5);
    const serverItems = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const currentError = ref("");
    let rowPermissions = {};
    
    const itemsPerPageOptions = [
        { value: 3, title: '3' },
        { value: 5, title: '5' },
        { value: 10, title: '10' }  
    ];
   
    const props = defineProps({
        settings: Object,
    });
    
    watch(
        () => props.settings,
        () => {
            getConfig().then(() => {
                configChange.value = !configChange.value;
            })
        }, {
            deep: true,
        }
    );
    
    async function getConfig() {
        try {
            const getParams = {
                viewType: props.settings.viewType,
                entityCode: props.settings.entityCode
            };
            configData.value = await useAjax("config/list", getParams);
            
            configData.value.fields.push({
                title: 'Actions', 
                key: 'actions', 
                sortable: false
            });
            
        } catch (error) {
            currentError.value = `Failed to get list config: ${error.message}`;
        }
    }
    
    await getConfig();
    
    async function loadData ({ page, itemsPerPage, sortBy }) {
        try {
            loading.value = true;
            const sortByParam = sortBy.length ? sortBy.toString() : "null";
            const getParams = {
                entityCode: props.settings.entityCode,
                page: page,
                itemsPerPage: itemsPerPage,
                sortBy: sortByParam,
            };
            const response = await useAjax("data/list", getParams);
            serverItems.value = response.data;
            totalItems.value = response.totalRows;
            rowPermissions = response.permissions;
            
        } catch (error) {
            serverItems.value = [];
            totalItems.value = 0;
            currentError.value = `Failed to get list data: ${error.message}`;
        } finally {
            loading.value = false;
        }
    }
    
    function updateItem(item) {
        console.log(item);
    }
    
    function viewItem(item) {
        console.log(item);
    }
    
    function deleteItem(item) {
        console.log(item);
    }

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
        :items-per-page-options="itemsPerPageOptions"
        :key="configChange"
    >
        <template v-slot:item.actions="{ item }">
            <v-icon
                v-if="rowPermissions[item.id].update"
                icon="$pencil"
                class="me-2"
                @click="updateItem(item)"
            />
            <v-icon
                v-if="rowPermissions[item.id].view"
                icon="$eye"
                class="me-2"
                @click="viewItem(item)"
            />
            <v-icon
                v-if="rowPermissions[item.id].delete"
                icon="$rubbish"
                class="me-2"
                @click="deleteItem(item)"
            />
        </template>
    </v-data-table-server>
</template>
