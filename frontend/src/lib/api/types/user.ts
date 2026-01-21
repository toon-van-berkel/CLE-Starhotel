export type User = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone: string;
	status_id: number | null;
	role_ids: number[]; // NEW
};
export type RegisterPayload = {
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    password: string;
};