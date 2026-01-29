<script lang="ts">
  import "../../scss/style.scss";
  import type { RoomsListResponse } from "$lib/api/client/apiTypes";

  export let data: { roomsData: RoomsListResponse };
  const { roomsData } = data;
</script>

<div class="rooms-page">
  <div class="rooms-header">
    <span class="subtitle">Onze Kamers</span>
    <h1>Kamers & Suites</h1>
    <p>Oog voor detail, ontworpen voor uw comfort.</p>
  </div>

  {#if !roomsData.records || roomsData.records.length === 0}
    <div class="error-state">
      <h2>No rooms available at the moment</h2>
      <p>Please contact our concierge for manual bookings.</p>
    </div>
  {:else}
    <div class="rooms-grid">
      {#each roomsData.records as room}
        <div class="room-card">
          <div class="image-wrapper">
            <img
              class="room-image"
              src="/Hotel-Kamer.png"
              alt="Room {room.number}"
            />
            <div class="status-badge">Luxe verblijf</div>
          </div>

          <div class="room-content">
            <div class="room-header">
              <h2>Room {room.number}</h2>
              <div class="capacity">
                <span>Max Guests: {room.max_capacity}</span>
              </div>
            </div>

            <p class="room-description">
              Ervaar elegantie in deze ruime kamer met op maat gemaakte meubels,
              luxe beddengoed en een prachtig uitzicht over de stad.
            </p>

            <div class="room-footer">
              <span class="price-indicator">Voor €249 / nacht</span>
              <a
                href={`rooms/room-${room.id}`}
                data-sveltekit-reload
                class="view-button"
              >
                Ondek deze kamer
              </a>
            </div>
          </div>
        </div>
      {/each}
    </div>
  {/if}
</div>
