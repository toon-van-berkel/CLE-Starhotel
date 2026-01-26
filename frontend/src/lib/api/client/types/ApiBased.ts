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
	ContactDeleteResponse,

	// Reservations
	ReservationsListResponse,
	ReservationRecordResponse,
	ReservationResponse,
	ReservationUpdateResponse,
	ReservationDeleteResponse
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

import type {
  ReservationInput,
  ReservationUpdateInput,
  ReservationDeleteInput
} from '$lib/api/types/reservation';

// Base
import type { Room } from '$lib/api/types/room';

// Other
import type { Get, GetById, Submit } from '$lib/api/client/types/ApiOther';

/* ------------------------------------------
 *              Endpoint maps
 * ------------------------------------------ */
// GET
export type ApiGetMap = {
	// Rooms
	rooms: Get<RoomsListResponse>;
	room: GetById<RoomRecordResponse>;

	// Contacts
	contacts: Get<ContactListResponse>;
	contact: GetById<ContactRecordResponse>;

	// Reservations
	reservations: Get<ReservationsListResponse>;
	reservation: GetById<ReservationRecordResponse>;

	// Auth
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

	// Reservations
	reservation: Submit<ReservationInput, ReservationResponse>;
	reservationUpdate: Submit<ReservationUpdateInput, ReservationUpdateResponse>;
	reservationDelete: Submit<ReservationDeleteInput, ReservationDeleteResponse>;

	// User
	login: Submit<LoginInput, LoginResponse>;
	register: Submit<RegisterInput, RegisterResponse>;
	logout: Submit<{}, LogoutResponse>;
};

// FETCH
export type FetchLike = (
	input: RequestInfo | URL,
	init?: RequestInit
) => Promise<Response>;
