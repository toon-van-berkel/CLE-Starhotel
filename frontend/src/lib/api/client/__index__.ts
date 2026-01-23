export type {SubmitEndpointsShape} from '$lib/api/client/apiSubmit';
export type {ApiError} from '$lib/api/client/apiBase';
export type {
    ApiGetKey,
    GetOutputForKey,
    GetInputForKey,
} from '$lib/api/client/apiCall'
export type {
    ApiSubmitKey,
    ApiEndpointKey,
    GetFunctionForKey,
    SubmitFunctionForKey,
    EndpointShape
} from '$lib/api/client/apiRoute';

export {api} from '$lib/api/client/apiBase';
export {apiCall} from '$lib/api/client/apiCall'
export {endpoints} from '$lib/api/client/apiRoute';
export {submitEndpoints, apiSubmit} from '$lib/api/client/apiSubmit';
