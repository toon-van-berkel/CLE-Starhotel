/* ------------------------------------------
 * Base
 * ------------------------------------------ */
export type Room = { 
    id: number; 
    max_capacity: number; 
    floor: number,
    wing: string,
    location: string,
    number: number
};

/* ------------------------------------------
 * Responses
 * ------------------------------------------ */
export type RoomsListResponse = {
    records: Room[];
    error?: string;
};
export type RoomRecordResponse = {
    record: Room | null;
    error?: string;
};