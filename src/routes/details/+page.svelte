<script>
  import images from '$lib/imageData/imageData.json';

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

  
</script>

<h1>Details</h1>

<div class="carousel-container">
  <div class="main-image-wrapper">
    <button class="nav-btn prev" on:click={prevImage} aria-label="Previous image">&lt;</button>
    <img class="main-image" src={images[currentIndex].src} alt={images[currentIndex].alt}>
    <button class="nav-btn next" on:click={nextImage} aria-label="Next image">&gt;</button>
  </div>

  <div class="indicators-images">
    {#each images as image, index}
      <button class="indicator {index === currentIndex ? 'active' : ''}" on:click={() => selectImage(index)} aria-label="Go to image {index + 1}">
        <img src={image.src} alt={image.alt} />
      </button>
    {/each}
  </div>

  <div class="thumbnails">
    {#each images as image, index}
      <button class="thumbnail-btn {index === currentIndex ? 'active' : ''}" on:click={() => selectImage(index)} aria-label="Select image {index + 1}">
        <img class="thumbnail-image" src={image.src} alt={image.alt}>
      </button>
    {/each}
  </div>
</div>