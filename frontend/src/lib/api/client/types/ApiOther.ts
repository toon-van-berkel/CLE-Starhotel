export type { Room } from '$lib/api/types/room';
export type { ContactInput } from '$lib/api/types/contact';

export type IdParam = { id: number };
export type Get<O> = { output: O };
export type GetWith<I, O> = { input: I; output: O };
export type GetById<O> = GetWith<IdParam, O>;
export type Submit<I, O> = { input: I; output: O };