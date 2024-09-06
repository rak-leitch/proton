import { RequestParams, RequestQueryParams } from "../types";

type RequestOptions = {
    method: string;
    headers: {
        [key: string]: string;
    };
    body?: string;
};

declare global {
  interface Window {
    protonApiBase: string;
  }
}

export class HttpMethod {
    name: string;
    needsCsrfToken: boolean;
    
    static Get = new HttpMethod('GET', false);
    static Post = new HttpMethod('POST', true);
    static Delete = new HttpMethod('DELETE', true);

    constructor(name: string, needsCsrfToken: boolean) {
        this.name = name;
        this.needsCsrfToken = needsCsrfToken;
    }
}
    
export async function request<T>({
    path,
    params = [], 
    queryParams = {}, 
    bodyData = {}, 
    method = null,
    acceptableErrors = [], 
} : {
    path: string, 
    params?: RequestParams, 
    queryParams?: RequestQueryParams, 
    bodyData?: Object, 
    method?: HttpMethod|null,
    acceptableErrors?: number[],
}): Promise<{ 
    status: number; 
    response: T;
}> {
    
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
        response: json as T,
    };
}

function getHttpMethod(bodyData: Object, specificMethod: HttpMethod|null): HttpMethod {
    
    let httpMethod: HttpMethod;
    
    if(specificMethod) {
        httpMethod = specificMethod;
    } else {
        httpMethod = Object.keys(bodyData).length ? HttpMethod.Post : HttpMethod.Get;
    }
    
    return httpMethod;
}

function getParameterString(params: RequestParams, queryParams: RequestQueryParams): string {
    
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

function getRequestOptions(httpMethod: HttpMethod, bodyData: Object): RequestOptions {
    
    const requestOptions: RequestOptions = {
        method: httpMethod.name,
        headers: {
            "Accept": "application/json",
        }
    };
    
    if(httpMethod.needsCsrfToken) { 
        const csrfTokenTag = document.querySelector("meta[name='csrf-token']");
        if(csrfTokenTag) {
            const csrfToken = csrfTokenTag.getAttribute("content") as string;
            if(csrfToken) {
                requestOptions.headers["X-Csrf-Token"] = csrfToken;
            }
        }
    }
    
    if(Object.keys(bodyData).length) {
        requestOptions.headers["Content-Type"] = "application/json";
        requestOptions.body = JSON.stringify(bodyData);
    }
    
    return requestOptions;
}
