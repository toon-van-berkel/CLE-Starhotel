import type {IdParam} from '$lib/api/client/types/ApiOther'

/* ------------------------------------------
 * Base
 * ------------------------------------------ */
export type Contact = {
    id: number;
    name: string;
    email: string;
    reason: string;
    title: string;
    message: string;
    status_id: number;
    admin_handled_id: number | null;
    created_at: string;
    handled_at: string | null;
    user_id: number | null;
};
export type ContactInput = {
    name: string;
    email: string;
    reason: string;
    title: string;
    message: string;
    user_id?: number | null;
};
export type ContactUpdateInput = IdParam & Partial<ContactInput> & {
	status_id?: number;
	admin_handled_id?: number | null;
	handled_at?: true | string | null;
	user_id?: number | null;
};
export type ContactDeleteInput = IdParam;

/* ------------------------------------------
 * Responses
 * ------------------------------------------ */
export type ContactListResponse = {
    records: Contact[];
    error?: string;
};
export type ContactRecordResponse = {
    record: Contact | null;
    error?: string;
};
export type ContactResponse =
    | { ok: true; id: number }
    | { ok: false; error: string; fields?: Record<string, string> };
export type ContactUpdateResponse =
	| { ok: true; record: Contact }
	| { ok: false; error: string; fields?: Record<string, string> };
export type ContactDeleteResponse =
	| { ok: true }
	| { ok: false; error: string };