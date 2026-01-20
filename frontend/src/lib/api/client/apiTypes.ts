import type {Room, RoomRecordResponse, RoomsListResponse} from '$lib/api/types/room';

// Getting map
export type ApiGetMap = {
    rooms: { output: RoomsListResponse };
    room: { input: { id: number }; output: RoomRecordResponse };
};

// Submit
export type ApiSubmitMap = {
    room: { input: Room; output: Room };
};

export type FetchLike = (input: RequestInfo | URL, init?: RequestInit) => Promise<Response>;
