export type User = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	status_id: number | null;
	role_ids: number[];
};
export type AuthUser = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	status_id: number | null;
	role_ids: number[];
	permission_ids?: number[];
};
export type RegisterPayload = {
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    password: string;
};

export type RefreshMeOptions = {
	force?: boolean;
};
export type MeResponseShape = {
	user?: User | null;
};