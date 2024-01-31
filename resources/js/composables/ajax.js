import { ref } from "vue";

export async function useAjax(path, parameters = [], bodyData = {}, method = "GET", acceptableStatuses = []) {

    const baseUrl = window.protonApiBase;
    let parameterString = "";
    
    const requestOptions = {
        method: method,
        headers: {
            "Accept": "application/json",
        }
    };
    
    for (const parameter of parameters) {
        parameterString += `${encodeURIComponent(parameter)}/`;
    }
    
    if(method === "POST") {
        const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        const postHeaders = {
            "Content-Type": "application/json",
            "X-Csrf-Token": csrfToken 
        };
        requestOptions["headers"] = {...requestOptions["headers"], ...postHeaders};
        requestOptions["body"] = JSON.stringify(bodyData);
    }
    
    const response = await fetch(`${baseUrl}/${path}/${parameterString}`, requestOptions);
    
    const body = await response.json();
    
    if (!response.ok && (!acceptableStatuses.includes(response.status))) {
        let message = `Received ${response.status} status code.`;
        
        if(body.hasOwnProperty('detail')) {
            message += ` ${body.detail}`;
        }
        
        throw new Error(message);
    }
    
    return { 
        statusCode: response.status,
        body: body,
    };
}
