<script setup>
    import protonList from "../components/ListComponent.vue";
    import { useAjax } from "../composables/ajax";
    import { useRoute } from "vue-router";
    import { ref, onMounted, watch } from "vue";
    
    const route = useRoute();
    const viewType = route.name;
    const entityCode = ref(route.params.entityCode);
    const label = ref("");
    const emit = defineEmits(["configError"]);
    
    watch(
        () => route.params.entityCode,
        () => {
            useAjax(`config/view/${viewType}/${route.params.entityCode}`).then((configData)  => {
                label.value = configData.entity_label_plural;          
                entityCode.value = configData.entity_code;
            });
        },
        { immediate: true },
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
