export type RegisterInput = {
	first_name: string;
	last_name: string;
	email: string;
	phone?: string;
	password: string;
};

export type LoginInput = {
	email: string;
	password: string;
};