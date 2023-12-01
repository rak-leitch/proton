import EntityIndexView from "./views/EntityIndexView.vue";

export default 
[
    {
        path: "/proton", 
        children: [
            {
                path: "entity/:entityCode/index",
                name: "entity-index",
                component: EntityIndexView
            }
        ]
    }
];
