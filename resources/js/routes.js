import EntityIndexView from "./views/EntityIndexView.vue";
import EntityCreateView from "./views/EntityCreateView.vue";
import EntityUpdateView from "./views/EntityUpdateView.vue";
import EntityDisplayView from "./views/EntityDisplayView.vue";
import NotFoundView from "./views/NotFoundView.vue";

export default 
[
    {
        path: "/proton", 
        children: [
            {
                path: "entity/:entityCode/index",
                name: "entity-index",
                component: EntityIndexView,
                props: true
            }, {
                path: "entity/:entityCode/create",
                name: "entity-create",
                component: EntityCreateView,
                props: true
            }, {
                path: "entity/:entityCode/update/:entityId",
                name: "entity-update",
                component: EntityUpdateView,
                props: true
            }, {
                path: "entity/:entityCode/display/:entityId",
                name: "entity-view",
                component: EntityDisplayView,
                props: true
            }, {
                path: ':pathMatch(.*)*',
                name: "not-found-view",
                component: NotFoundView
            }
        ]
    }
];
