import type {User} from '$lib/api/types/__index__';

export type AuthState = {
    user: User | null;
    loading: boolean;
    initialized: boolean;
};