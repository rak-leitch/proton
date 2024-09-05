export type RequestParams = (string|number)[];

export type RequestQueryParams = {
    [key: string]: string;
};

export type DisplayComponentSettings = {
    entityCode: string,
    entityId: string,
};

export type FormComponentSettings = {
    entityCode: string;
    entityId: string|null;
    configPath: string;
    submitPath: string;
    contextCode: string;
    contextId: string;
};

export type ListComponentSettings = {
    entityCode: string;
    contextCode: string;
    contextId: string;
};
