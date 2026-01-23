/* ------------------------------------------
 *               Type imports
 * ------------------------------------------ */
// Responses
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

	// Contact
	ContactUpdateResponse,
	ContactDeleteResponse
} from '$lib/api/client/types/ApiResponse';

// Inputs + Submits
import type {
	ContactInput,
	ContactDeleteInput,
	ContactUpdateInput
} from '$lib/api/types/contact';
import type {
	LoginInput,
	RegisterInput
} from '$lib/api/types/auth';

// Base
import type {Room} from '$lib/api/types/room';

// Other
import type {
	Get, GetById, Submit
} from '$lib/api/client/types/ApiOther';

/* ------------------------------------------
 *              Endpoint maps
 * ------------------------------------------ */
// GET
export type ApiGetMap = {
	rooms: Get<RoomsListResponse>;
	room: GetById<RoomRecordResponse>;

	contacts: Get<ContactListResponse>;
	contact: GetById<ContactRecordResponse>;

	me: Get<MeResponse>;
};

// POST
export type ApiSubmitMap = {
	// Rooms
	room: Submit<Room, Room>;

	// Contact
	contact: Submit<ContactInput, ContactResponse>;
	contactUpdate: Submit<ContactUpdateInput, ContactUpdateResponse>;
	contactDelete: Submit<ContactDeleteInput, ContactDeleteResponse>;

	// User
	login: Submit<LoginInput, LoginResponse>;
	register: Submit<RegisterInput, RegisterResponse>;
	logout: Submit<{}, LogoutResponse>;
};

// FETCH
export type FetchLike = (input: RequestInfo | URL, init?: RequestInit) => Promise<Response>;
