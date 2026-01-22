import type { Room } from "$lib/api/types/room";
import type { Contact } from "$lib/api/types/contact";
import type { User } from "$lib/api/types/user";

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
export type AuthUser = {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  status_id: number;
  role_ids: number[];
  roles: { id: number; name: string }[];
};
export type MeResponse =
  | { ok: true; user: AuthUser }
  | { ok: true; user: null };
export type LoginResponse =
  | { ok: true; user: AuthUser }
  | { ok: false; error: string };
export type RegisterResponse =
  | { ok: true; id: number }
  | { ok: false; error: string; fields?: Record<string, string> };
export type LogoutResponse = { ok: true };
