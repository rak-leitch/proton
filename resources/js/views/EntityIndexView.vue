<script setup>
    import protonList from "../components/ListComponent.vue";
    import { useAjax } from "../composables/ajax";
    import { useRoute } from "vue-router";
    import { ref, watch } from "vue";
    
    const emit =  defineEmits(['reloadView']);
    const route = useRoute();
    const viewType = route.name;
    const entityCode = ref(route.params.entityCode);
    const label = ref("");
    
    let configData = await useAjax(`config/view/${viewType}/${route.params.entityCode}`);
    label.value = configData.entity_label_plural;

    watch(
        () => route.params,
        () => {
            emit('reloadView');
        }
    )
    
</script>

<template>
    <v-card class="my-4" elevation="4">
        <template v-slot:title>
            {{ label }}
        </template>
        <template v-slot:text>
            <protonList 
                :entity-code="entityCode"
                :view-type="viewType"
            />
        </template>
    </v-card>
</template>
