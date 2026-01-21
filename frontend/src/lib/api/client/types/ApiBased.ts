import type {Room} from '$lib/api/types/room';
import type { ContactInput } from '$lib/api/types/contact';
import type {
    // Rooms
    RoomsListResponse, 
    RoomRecordResponse, 

    // Tickets (Contacts)
    ContactListResponse, 
    ContactRecordResponse,
    ContactResponse,
} from '$lib/api/client/types/ApiResponse';

// GET
export type ApiGetMap = {
    rooms: { output: RoomsListResponse };
    room: { input: { id: number }; output: RoomRecordResponse };

  contacts: { output: ContactListResponse };
  contact: { input: { id: number }; output: ContactRecordResponse };
};

// POST
export type ApiSubmitMap = {
    room: { input: Room; output: Room };
    contact: { input: ContactInput; output: ContactResponse };
};

// FETCH
export type FetchLike = (input: RequestInfo | URL, init?: RequestInit) => Promise<Response>;