<script lang="ts">
  import { Datepicker } from "svelte-calendar";

  // 1. Instellingen voor de datums
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);

  let checkIn = tomorrow;
  let checkOut = new Date();
  checkOut.setDate(tomorrow.getDate() + 2);

  // 2. Validatie check
  $: isValid = checkOut > checkIn;

  // 3. notification logica
  let loadMessage = false;
  let message = "";

  function showNotification(text: string) {
    message = text;
    loadMessage = true;

    // Na 3 seconden gaat de melding weer weg
    setTimeout(() => {
      loadMessage = false;
    }, 3000);
  }

  function handleBooking() {
    if (isValid) {
      showNotification("🎉 Je reservering is opgeslagen!");
    }
  }

  function toonDatum(datum: Date) {
    return datum.toLocaleDateString("nl-NL", { day: "numeric", month: "long" });
  }
</script>

{#if loadMessage}
  <div class="notification">
    {message}
  </div>
{/if}

<div class="container">
  <h2>Reserveer een Kamer</h2>

  <div class="datums">
    <div class="check">
      <p>Check-in</p>
      <Datepicker bind:selected={checkIn} start={tomorrow} />
      <p class="small">{toonDatum(checkIn)}</p>
    </div>

    <div class="check">
      <p>Check-out</p>
      <div class:fout={!isValid}>
        <Datepicker bind:selected={checkOut} start={tomorrow} />
      </div>
      <p class="small">{toonDatum(checkOut)}</p>
    </div>
  </div>

  <button disabled={!isValid} on:click={handleBooking}>
    Bevestig Boeking
  </button>
</div>

<style>
  /* De styling voor de notificatie rechtsboven */
  .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    font-weight: bold;
    /* Een simpele animatie van boven naar beneden */
    animation: slideIn 0.3s ease-out;
  }

  @keyframes slideIn {
    from {
      transform: translateX(+200px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  /* Basis styling */
  .container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 12px;
    font-family: sans-serif;
  }
  .datums {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
  }
  .check {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .small {
    color: #0f4a5a;
    font-weight: bold;
    margin-top: 5px;
  }
  .fout {
    border: 2px solid red;
    border-radius: 4px;
  }

  button {
    width: 100%;
    padding: 12px;
    background: #0f4a5a;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
  }
  button:disabled {
    background: #ccc;
    cursor: not-allowed;
  }
</style>
