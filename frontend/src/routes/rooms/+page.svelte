<script lang="ts">
    import type { RoomsListResponse } from '$lib/api/client/apiTypes';

    export let data: { roomsData: RoomsListResponse };
    const { roomsData } = data;
</script>

<!-- {#each roomsData.records as room}
    <div>Room {room.number} <a href="/rooms/room-{room.id}">Room {room.id}</a> </div>
{/each} -->

<div class="rooms-container">
{#each roomsData.records as room}
    {#if !roomsData.records}
        <h2>Fatal error, no rooms available</h2>
    {/if}
    <div class="room-container">
        <div class="popular">Popular</div>
        <img class="room-image" src="" alt="Room {room.id} image">
        <div class="room-body">
            <h2>Deluxe Room</h2>
            <article>
                <p class=room-pricing><bold>&euro;149,99</bold> <small>Per night.</small></p>
                <p class="room-description">Room description goes here!</p>
            </article>
            <div class="room-data">
                <a href={`rooms/room-${room.id}`} data-sveltekit-reload>Bekijk kamer</a>
                <p><small>Max: {room.max_capacity}</small></p>
            </div>
            
            <!-- Room detail information! -->
            <!-- <p>Max capacity: {room.max_capacity}</p>
            <p>Current capacity{room.current_capacity}</p>
            <p>Floor: {room.floor}</p>
            <p>Location: {room.location}</p>
            <p>Wing: {room.wing}</p>
            <p>Number: {room.number}</p>
         -->
            <!--Might need to add room description, pricing etc-->
        </div>
        <!-- <p>id: {room.id}</p> -->
        
    </div>


{/each}
</div>
 