import type { Room } from "$lib/api/types/room";
import type { ContactInput } from "$lib/api/types/contact";
import type { RegisterInput, LoginInput } from "$lib/api/types/auth";

import type {
  // Rooms
  RoomsListResponse,
  RoomRecordResponse,

  // Tickets (Contacts)
  ContactListResponse,
  ContactRecordResponse,
  ContactResponse,

  // Auth
  MeResponse,
  LoginResponse,
  RegisterResponse,
  LogoutResponse,
} from "$lib/api/client/types/ApiResponse";

// GET
export type ApiGetMap = {
  rooms: { output: RoomsListResponse };
  room: { input: { id: number }; output: RoomRecordResponse };

  contacts: { output: ContactListResponse };
  contact: { input: { id: number }; output: ContactRecordResponse };

  me: { output: MeResponse };
};

// POST
export type ApiSubmitMap = {
  room: { input: Room; output: Room };
  contact: { input: ContactInput; output: ContactResponse };

  register: { input: RegisterInput; output: RegisterResponse };
  login: { input: LoginInput; output: LoginResponse };
  logout: { input: Record<string, never>; output: LogoutResponse };
};

// FETCH
export type FetchLike = (
  input: RequestInfo | URL,
  init?: RequestInit
) => Promise<Response>;
