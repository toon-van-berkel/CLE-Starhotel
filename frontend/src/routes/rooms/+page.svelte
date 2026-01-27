<script lang="ts">
    import type { RoomsListResponse } from '$lib/api/client/apiTypes';

    export let data: { roomsData: RoomsListResponse };
    const { roomsData } = data;
</script>

<div class="rooms-container">
    {#each roomsData.records as room}
        {#if !roomsData.records}
            <h2>Fatal error, no rooms available</h2>
        {/if}
        <div class="room-container">
            <!-- <div class="popular">Popular</div> -->
            <img class="room-image" src="/Hotel-Kamer.png" alt="Room {room.id} image">
            <div class="room-body">
                <h2>Room: {room.number}</h2>
                <p class="room-description">Room description goes here!</p>
                <div class="room-data">
                    <a href={`rooms/room-${room.id}`} data-sveltekit-reload>Bekijk kamer</a>
                    <p><small>Max: {room.max_capacity}</small></p>
                </div>
            </div>
        </div>
    {/each}
</div>
 