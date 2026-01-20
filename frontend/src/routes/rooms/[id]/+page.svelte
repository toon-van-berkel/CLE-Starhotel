<!-- <script lang="ts">
    import "../../scss/_details.scss";
    import { onMount } from 'svelte';
    import { detailPage } from '$lib/api/room/records';
    import type { Room } from '$lib/api/types/room';


	let id = $props();

let room: Room;

    onMount(async () => {
        const res = await detailPage(id);
        room = res.room;
        console.log(room);
    });

  let currentIndex = 0;

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
  }

  function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
  }

  /** @param {number} index */
  function selectImage(index) {
    currentIndex = index;
  }
</script> -->

<!-- <script lang="ts">
  import type { Room } from '$lib/api/types/room';

  let { data } = $props<{
    data: { room: Room | null; error: string | null };
  }>();
</script>

{#if data.error}
  <p>{data.error}</p>
{:else if !data.room}
  <p>Room not found.</p>
{:else}
  <h1>Room {data.room.number}</h1>
  <ul>
    <li>Floor: {data.room.floor}</li>
    <li>Wing: {data.room.wing}</li>
    <li>Max: {data.room.max_capacity}</li>
    <li>Current: {data.room.current_capacity}</li>
    <li>Location: {data.room.location}</li>
  </ul>
{/if} -->

<script lang="ts">
  import type { Room } from '$lib/api/types/room';

  let { data } = $props<{
    data: {
      room: Room | null;
      error: string | null;
    };
  }>();


</script>

{#if data.error}
  <p>{data.error}</p>
{:else if !data.room}
  <p>Room not found.</p>
{:else}
  <h1>Room {data.room.number}</h1>
  <ul>
    <li>Floor: {data.room.floor}</li>
    <li>Wing: {data.room.wing}</li>
    <li>Max: {data.room.max_capacity}</li>
    <li>Current: {data.room.current_capacity}</li>
    <li>Location: {data.room.location}</li>
  </ul>
{/if}

<!-- 
<div class="details-container">
  <div class="details-intro">
    <h1 class="details-header">Deluxe Room</h1>
    <p>
      De Deluxe Room biedt een comfortabel en luxueus verblijf met een
      uitstekende uitzicht op de stad of het landschap.
    </p>
  </div>
  <div class="content-column">
    <div class="carousel-container">
      <div class="main-image-wrapper">
        <button
          class="nav-btn prev"
          onclick={prevImage}
          aria-label="Previous image">←</button
        >
        <img
          class="main-image"
          src={images[currentIndex].src}
          alt={images[currentIndex].alt}
        />
        <button
          class="nav-btn next"
          onclick={nextImage}
          aria-label="Next image">→</button
        >
        <button class="overlay-btn">Klik om foto te vergroten</button>
      </div>

      <div class="indicators-images">
        {#each images as image, index}
          <button
            class="indicator {index === currentIndex ? 'active' : ''}"
            onclick={() => selectImage(index)}
            aria-label="Go to image {index + 1}"
          >
            <img src={image.src} alt={image.alt} />
          </button>
        {/each}
      </div>
      <div class="thumbnails">
        {#each images as image, index}
          <button
            class="thumbnail-btn {index === currentIndex ? 'active' : ''}"
            onclick={() => selectImage(index)}
            aria-label="Select image {index + 1}"
          >
            <img class="thumbnail-image" src={image.src} alt={image.alt} />
          </button>
        {/each}
      </div>
    </div>

    <div class="room-details-card">
      <h2>Kamer details</h2>
      <div class="facilities-container">
        <div class="facility-card">
          <h3>Faciliteiten</h3>
          <ul>
            <li>King-size bed</li>
            <li>Regendouche</li>
            <li>Snelle Wifi</li>
            <li>4K Smart TV</li>
          </ul>
        </div>
        <div class="facility-card">
          <h3>Also includes</h3>
          <ul>
            <li>Ontbijt</li>
            <li>Gym</li>
            <li>Zwembad</li>
            <li>24/7 room service</li>
            <li>Gratis parkeren</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="reservation-card">
    <div class="reservation-content">
      <h2>Reserveren</h2>
      <p class="price">Voor €249,- per nacht</p>
      <button class="reserve-btn">Reserveer deze kamer</button>
      <a href="/rooms">
        <button class="rooms-btn">Terug naar Rooms</button>
      </a>
    </div>
  </div>
</div> -->
