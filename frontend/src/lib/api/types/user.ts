export type User = {
    id: number;
    role_id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    status_id: number | null;
};
export type RegisterPayload = {
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    password: string;
};