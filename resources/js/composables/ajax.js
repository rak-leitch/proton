import { ref } from "vue";

export async function useAjax(url, requestData = {}, method = "GET") {

    const baseUrl = window.protonApiBase;
    let getParams = "";
    
    const requestOptions = {
        method: method,
    };
    
    if(method === "GET") {
        getParams += '/';
        for (const property in requestData) {
            getParams += `${encodeURIComponent(requestData[property])}/`;
        }
    }
    
    if(method === "POST") {
        requestOptions["headers"] = {
            "Content-Type": "application/json",
        };
        requestOptions["body"] = JSON.stringify(requestData);
    }
    
    const response = await fetch(`${baseUrl}/${url}${getParams}`, requestOptions);
    
    const data = await response.json();
    
    if (!response.ok) {
        let message = `Received ${response.status} status code.`;
        
        if(data.hasOwnProperty('detail')) {
            message += ` ${data.detail}`;
        }
        
        throw new Error(message);
    }
    
    return data;
}
