<script lang="ts">
  import type { RoomRecordResponse } from "$lib/api/client/apiTypes";
  import images from "$lib/imageData/imageData.json";
  export let data: { roomData: RoomRecordResponse };

  let currentIndex = 0;
  // 1. Variabele voor de status
  let showOverlay = false;

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
  }

  function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
  }

  function selectImage(index: number) {
    currentIndex = index;
  }

  // 2. Functie om te openen/sluiten
  function toggleOverlay() {
    showOverlay = !showOverlay;
  }
</script>

{#if data.roomData.record}
  <div class="details-container">
    <div class="details-intro">
      <h1 class="details-header">Room {data.roomData.record.number}</h1>
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
          <button class="overlay-btn" onclick={toggleOverlay}
            >Klik om foto te vergroten</button
          >
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
    </div>
    <div class="reservation-card">
      <div class="reservation-content">
        <h2>Reserveren</h2>
        <a
          href="/rooms/room-{data.roomData.record.id}/book-{data.roomData.record
            .id}"
          class="reserve-btn">Reserveer deze kamer</a
        >
        <a href="/rooms">
          <button class="rooms-btn">Terug naar Rooms</button>
        </a>
      </div>
    </div>
  </div>
{:else}
  <div>Room not found</div>
{/if}

{#if showOverlay}
  <div
    class="image-lightbox"
    role="button"
    tabindex="0"
    onclick={toggleOverlay}
    onkeydown={(e) => e.key === "Escape" && toggleOverlay()}
  >
    <div
      class="lightbox-content"
      role="none"
      onclick={(e) => e.stopPropagation()}
    >
      <button class="close-overlay" onclick={toggleOverlay}>Sluiten ×</button>

      <div class="lightbox-main">
        <button class="nav-btn prev" onclick={prevImage}>←</button>
        <img src={images[currentIndex].src} alt={images[currentIndex].alt} />
        <button class="nav-btn next" onclick={nextImage}>→</button>
      </div>

      <div class="thumbnails lightbox-thumbs">
        {#each images as image, index}
          <button
            class="thumbnail-btn {index === currentIndex ? 'active' : ''}"
            onclick={() => selectImage(index)}
          >
            <img class="thumbnail-image" src={image.src} alt={image.alt} />
          </button>
        {/each}
      </div>
    </div>
  </div>
{/if}
