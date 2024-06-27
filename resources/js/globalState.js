import { reactive, computed, onMounted } from 'vue'
import lv_lang from './Languages/LV.js'
import en_lang from './Languages/EN.js'

const globalState = reactive({
    // default settings
    settings: {
        language: "en",
    }, 

    lang: computed(() => {
        switch(globalState.settings.language){
            case "lv": 
                return lv_lang;
            default: 
                console.log("language '" + globalState.settings.language + "' not found");
            case "en":
                return en_lang;
        }
    }),
})

export default globalState;