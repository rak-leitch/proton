import { ref } from "vue";

export async function useAjax(url) {

    const baseUrl = window.protonApiBase;
    let response = null;
    let data = null;
    
    try {
        response = await fetch(`${baseUrl}/${url}`);
        data = await response.json();
    } catch(error) {
        throw new Error(`Failed to make request - ${error.message}`);
    }
    
    return data;
}
