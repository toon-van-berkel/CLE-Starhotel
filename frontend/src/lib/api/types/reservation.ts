import type { IdParam } from '$lib/api/client/types/ApiOther';

/* ------------------------------------------
 * Base
 * ------------------------------------------ */
export type ReservationApiUser = {
	id: number;
	first_name: string;
	last_name: string;
	email: string;
	phone?: string;
};
export type ReservationApiRecord = {
	id: number;
	user_id: number;
	status_id: number;
	checked_in: 0 | 1;
	checked_in_at: string | null;
	checked_out_at: string | null;
	booked_at: string;
	booked_from: string;
	booked_till: string;
	payment_method: string;

	room_id: number;
	room_ids: number[];

	user?: ReservationApiUser;
};
export type ReservationInput = {
	booked_from: string; // YYYY-MM-DD
	booked_till: string; // YYYY-MM-DD
	payment_method: string;
	room_id: number;
};
export type ReservationUpdateInput = IdParam & Partial<ReservationInput> & {
	status_id?: number;
	checked_in?: 0 | 1;
	checked_in_at?: true | string | null;
	checked_out_at?: true | string | null;
};
export type ReservationDeleteInput = IdParam;

/* ------------------------------------------
 * Responses
 * ------------------------------------------ */
export type ReservationsListResponse = {
	records: ReservationApiRecord[];
	error?: string;
};
export type ReservationRecordResponse = {
	record: ReservationApiRecord | null;
	error?: string;
};

// create => record terug + user mee
export type ReservationResponse =
	| { ok: true; id: number; record: ReservationApiRecord | null }
	| { ok: false; error: string; fields?: Record<string, string> };
export type ReservationUpdateResponse =
	| { ok: true; record: ReservationApiRecord }
	| { ok: false; error: string; fields?: Record<string, string> };
export type ReservationDeleteResponse =
	| { ok: true }
	| { ok: false; error: string };
