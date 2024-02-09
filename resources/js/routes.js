import EntityIndexView from "./views/EntityIndexView.vue";
import EntityCreateView from "./views/EntityCreateView.vue";
import EntityUpdateView from "./views/EntityUpdateView.vue";
import EntityViewView from "./views/EntityViewView.vue";

export default 
[
    {
        path: "/proton", 
        children: [
            {
                path: "entity/:entityCode/index",
                name: "entity-index",
                component: EntityIndexView
            }, {
                path: "entity/:entityCode/create",
                name: "entity-create",
                component: EntityCreateView
            }, {
                path: "entity/:entityCode/update/:entityId",
                name: "entity-update",
                component: EntityUpdateView
            }, {
                path: "entity/:entityCode/view",
                name: "entity-view",
                component: EntityViewView
            }
        ]
    }
];
