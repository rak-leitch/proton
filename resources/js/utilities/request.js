export async function request(path, parameters = [], queryParams = {}, postData = {}) {

    const getVerb = 'GET';
    const postVerb = 'POST';
    const baseUrl = window.protonApiBase;
    const method = Object.keys(postData).length ? postVerb : getVerb;
    const acceptableErrors = [ 422 ];
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
    
    if(Object.keys(queryParams).length > 0) {
        const queryString = new URLSearchParams(queryParams);
        parameterString += `?${queryString}`;
    }
    
    if(method === postVerb) {
        const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        const postHeaders = {
            "Content-Type": "application/json",
            "X-Csrf-Token": csrfToken 
        };
        requestOptions["headers"] = {...requestOptions["headers"], ...postHeaders};
        requestOptions["body"] = JSON.stringify(postData);
    }
    
    const response = await fetch(`${baseUrl}/${path}/${parameterString}`, requestOptions);
    
    const json = await response.json();
    
    if (!response.ok && (!acceptableErrors.includes(response.status))) {
        let message = `Received ${response.status} status code.`;
        
        if(json.hasOwnProperty('detail')) {
            message += ` ${json.detail}`;
        }
        
        throw new Error(message);
    }
    
    return { 
        status: response.status,
        json: json,
    };
}
