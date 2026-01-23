export type { 
	ApiGetMap, 
	ApiSubmitMap,
	FetchLike 
} from '$lib/api/client/types/ApiBased';
export type {
	IdParam,
	Get,
	GetWith,
	GetById,
	Submit
} from '$lib/api/client/types/ApiOther'
export type {
	// Rooms
    RoomsListResponse,
    RoomRecordResponse,

	// Tickets (Contacts)
    ContactDeleteResponse,
    ContactListResponse,
    ContactRecordResponse,
    ContactResponse,
    ContactUpdateResponse,

	// Auth
    MeResponse,
    LoginResponse,
    RegisterResponse,
    LogoutResponse
} from '$lib/api/client/types/ApiResponse';