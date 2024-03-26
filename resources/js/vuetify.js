import "vuetify/styles";
import { createVuetify } from "vuetify";
import { aliases, mdi } from "vuetify/iconsets/mdi-svg";
import { mdiPencil, mdiEyeOutline, mdiDeleteOutline } from "@mdi/js";

const vuetify = createVuetify({
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
