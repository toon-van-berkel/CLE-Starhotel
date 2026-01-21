import type { Room } from '$lib/api/types/room';
import type { Contact } from '$lib/api/types/contact';
import type { User } from '$lib/api/types/user';

// Rooms
export type RoomsListResponse = {
	records: Room[];
	error?: string;
};
export type RoomRecordResponse = {
	record: Room | null;
	error?: string;
};

// Tickets (Contacts)
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

// Auth
export type MeResponse = { user: User };
export type LoginResponse = { ok: true; user: User };
export type RegisterResponse = { ok: true };
export type LogoutResponse = { ok: true };