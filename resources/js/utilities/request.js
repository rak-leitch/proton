export class HttpMethod {
 
    static Get = new HttpMethod('GET', false);
    static Post = new HttpMethod('POST', true);
    static Delete = new HttpMethod('DELETE', true);

    constructor(name, needsCsrfToken) {
        this.name = name;
        this.needsCsrfToken = needsCsrfToken;
    }
}

export async function request({
    path, 
    params = [], 
    queryParams = {}, 
    bodyData = {}, 
    method = null,
    acceptableErrors = [],
}) {
    
    const baseUrl = window.protonApiBase;
    const httpMethod = getHttpMethod(bodyData, method);
    const parameterString = getParameterString(params, queryParams);
    const requestOptions = getRequestOptions(httpMethod, bodyData);
    
    const response = await fetch(`${baseUrl}/${path}/${parameterString}`, requestOptions);
    const json = await response.json();
    
    if (!response.ok && (!acceptableErrors.includes(response.status))) {
        let message = `Received ${response.status} status code.`;
        
        if(Object.hasOwn(json, 'detail')) {
            message += ` ${json.detail}`;
        }
        
        throw new Error(message);
    }
    
    return { 
        status: response.status,
        json: json,
    };
}

function getHttpMethod(bodyData, specificMethod) {
    
    let httpMethod = null;
    
    if(specificMethod) {
        httpMethod = specificMethod;
    } else {
        httpMethod = Object.keys(bodyData).length ? HttpMethod.Post : HttpMethod.Get;
    }
    
    return httpMethod;
}

function getParameterString(params, queryParams) {
    
    let parameterString = "";
    
    for (const param of params) {
        parameterString += `${encodeURIComponent(param)}/`;
    }
    
    if(Object.keys(queryParams).length > 0) {
        const queryString = new URLSearchParams(queryParams);
        parameterString += `?${queryString}`;
    }
    
    return parameterString;
}

function getRequestOptions(httpMethod, bodyData) {
    
    const requestOptions = {
        method: httpMethod.name,
        headers: {
            "Accept": "application/json",
        }
    };
    
    if(httpMethod.needsCsrfToken) { 
        const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        requestOptions["headers"]["X-Csrf-Token"] = csrfToken;
    }
    
    if(Object.keys(bodyData).length) {
        requestOptions["headers"]["Content-Type"] = "application/json";
        requestOptions["body"] = JSON.stringify(bodyData);
    }
    
    return requestOptions;
}
