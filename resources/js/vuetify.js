import "vuetify/styles";
import { createVuetify } from "vuetify";
import { aliases, mdi } from "vuetify/iconsets/mdi-svg";
import { mdiPencil, mdiEyeOutline, mdiDeleteOutline } from "@mdi/js";

//TODO: Won't need to import everything
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components: components,
    directives: directives,
    icons: {
        defaultSet: "mdi",
        aliases: {
            ...aliases,
            pencil: mdiPencil,
            eye: mdiEyeOutline,
            rubbish: mdiDeleteOutline,
            
        },
        sets: {
            mdi,
        },
    }
});

export default vuetify;
