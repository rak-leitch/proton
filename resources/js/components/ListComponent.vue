<script setup>
    import { ref, watch } from 'vue';
    import { useAjax } from "../composables/ajax";
    
    const baseUrl = window.protonApiBase;
    const itemsPerPage = ref(5);
    const serverItems = ref([]);
    const headers = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const page = ref(1);
    const emit = defineEmits(["configError"]);
    const configData = ref({});
   
    const props = defineProps({
        entityCode: String,
        viewType: String,
    });
    
    function loadData ({ page, itemsPerPage, sortBy }) {

        loading.value = true;
        
        //Dummy fetch for the time being.
        serverItems.value = [
            { id: 1, name: 'Project 1', description: 'Go down the pub', priority: 'Urgent' },
            { id: 2, name: 'Project 2', description: 'Boring housework', priority: 'Low' },
        ];
        
        totalItems.value = 2;
        
        loading.value = false;
    }

    let config = await useAjax(`config/list/${props.viewType}/${props.entityCode}`);
    headers.value = config.fields;

</script>

<template>
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="headers"
        :items-length="totalItems"
        :items="serverItems"
        :loading="loading"
        :page="page"
        item-value="name"
        @update:options="loadData"
    ></v-data-table-server>
</template>
