<script lang="ts">
    import { onMount } from "svelte";
    import { records } from "$lib/api/room/records";
    import type { Room } from "$lib/api/types/room";  
    

    let rooms: Room[] = [];
    let error: string | null = null;

    onMount(async () => {
        const res = await records();
        rooms = res.records;
        error = res.error ?? null;
    });

  // export let room: Room;
</script>

<!-- <h2>Hello World!</h2> -->
<div class="rooms-container">
{#each rooms as room}
    {#if !rooms}
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
                <a href={`rooms/${room.id}`} data-sveltekit-reload>Bekijk kamer</a>
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
