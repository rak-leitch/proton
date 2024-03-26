<script setup>
    import { useRouter } from "vue-router";
    import { request } from "../utilities/request";
    import { ref, computed } from "vue";
    
    const router = useRouter();
    const menuConfig = ref([]);
    const errorMessage = ref('');
    
    async function getConfig() {
        try {
            const { json } = await request({
                path: "config/menu"
            });
            initialiseMenu(json);
        } catch (error) {
            errorMessage.value = "Menu setup failed";
        }
    }
    
    function initialiseMenu(json) {
        json.entities.forEach(function(entity) {
            const entityConfig = {
                route: {
                    name: 'entity-index',  
                    params: { 
                        entityCode: entity.entityCode 
                    },
                },
                label: entity.label,
                code: entity.entityCode,
            };
            menuConfig.value.push(entityConfig);
        }); 
    }
    
    const displayMenu = computed(() => {
        return (Object.keys(menuConfig.value).length) && !errorMessage.value;
    });
    
    getConfig();
    
</script>

<template>
    <v-menu v-if="displayMenu">
        <template v-slot:activator="{ props }">
            <v-btn
                color="white"
                v-bind="props"
                class="index-menu-button"
            >
                View Entities
            </v-btn>
        </template>
        <v-list>
            <v-list-item
                v-for="(entity) in menuConfig"
            >
                <v-list-item-title>
                    <router-link 
                        :to="entity.route"
                        :class="`entity-index-${entity.code} text-decoration-none text-black`"
                    >
                        {{ entity.label }}
                    </router-link>
                </v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
    <div v-if="errorMessage">
        {{ errorMessage }}
    </div>
</template>
