<script setup>
    import { useAjax } from "../composables/ajax";
    import { useRoute } from "vue-router";
    import { ref } from "vue";
    
    const label = ref("projects");
</script>

<template>
    <v-card class="my-4" elevation="4">
        <template v-slot:title>
            This is the view for {{ label }}
        </template>
        <template v-slot:text>
            Content goes here.
        </template>
    </v-card>
</template>
