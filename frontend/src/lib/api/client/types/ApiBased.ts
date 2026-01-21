import type { Room } from '$lib/api/types/room';
import type { ContactInput } from '$lib/api/types/contact';
import type { RegisterInput, LoginInput } from '$lib/api/types/auth';

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
	LogoutResponse
} from '$lib/api/client/types/ApiResponse';

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

	login: { input: { email: string; password: string }; output: LoginResponse };
	register: {
		input: { first_name: string; last_name: string; email: string; phone: string; password: string };
		output: RegisterResponse;
	};
	logout: { input: {}; output: LogoutResponse };
};

// FETCH
export type FetchLike = (input: RequestInfo | URL, init?: RequestInit) => Promise<Response>;
