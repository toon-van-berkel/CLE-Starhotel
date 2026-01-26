/* ------------------------------------------
 * Base
 * ------------------------------------------ */
export type User = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    status_id: number;
    role_ids: number[];
    roles: { id: number; name: string }[];
};
export type LoginInput = { email: string; password: string };
export type RegisterInput = {
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	password: string;
}

/* ------------------------------------------
 * Responses
 * ------------------------------------------ */
export type MeResponse =
    | { ok: true; user: User }
    | { ok: true; user: null };
export type LoginResponse =
    | { ok: true; user: User }
    | { ok: false; error: string };
export type RegisterResponse =
    | { ok: true; id: number }
    | { ok: false; error: string; fields?: Record<string, string> };
export type LogoutResponse = { ok: true };
