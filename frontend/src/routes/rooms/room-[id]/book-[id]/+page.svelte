<script lang="ts">
  import "../../../../scss/style.scss";

  import { goto } from "$app/navigation";
  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import type { ApiError } from "$lib/api/client/apiBase";
  import type { ReservationApiRecord } from "$lib/api/types/reservation";

  // form fields
  let room_id = $props();
  let booked_from = $state("");
  let booked_till = $state("");
  let payment_method = $state("");

  // ui state
  let submitting = $state(false);
  let formError = $state("");
  let fieldErrors = $state<Record<string, string>>({});
  let created = $state<ReservationApiRecord | null>(null);

  function resetErrors() {
    formError = "";
    fieldErrors = {};
  }

  function clientValidate(): boolean {
    const fe: Record<string, string> = {};

    if (!booked_from) fe.booked_from = "Kies een startdatum";
    if (!booked_till) fe.booked_till = "Kies een einddatum";
    if (booked_from && booked_till && booked_till < booked_from) {
      fe.booked_till = "De vertrekdatum moet na de aankomstdatum liggen";
    }
    if (!payment_method.trim()) fe.payment_method = "Kies een betaalmethode";

    fieldErrors = fe;
    return Object.keys(fe).length === 0;
  }

  async function submitReservation(e?: SubmitEvent) {
    e?.preventDefault();
    resetErrors();
    created = null;
    if (!clientValidate()) return;

    submitting = true;
    try {
      const res = await apiSubmit("reservation", fetch, {
        room_id,
        booked_from,
        booked_till,
        payment_method,
      });

      if ("ok" in res && res.ok === true) {
        created = (res as any).record ?? null;
        // Een kleine vertraging voor de 'success' ervaring
        setTimeout(() => goto("/rooms"), 1500);
        return;
      }

      formError = (res as any)?.error ?? "Reservering mislukt";
      fieldErrors = (res as any)?.fields ?? {};
    } catch (err) {
      const e = err as ApiError;
      const data = (e.data ?? {}) as any;
      formError = data.error ?? e.message ?? "Er is iets misgegaan";
      fieldErrors = data.fields ?? {};
    } finally {
      submitting = false;
    }
  }
</script>

<section class="page reservation-page">
  <div class="reservation-intro">
    <span class="subtitle">Bijna klaar voor uw verblijf</span>
    <h1>Bevestig uw reservering</h1>
    <p>
      Vul de onderstaande details in om uw kamer in Starhotel definitief vast te
      leggen. We kijken ernaar uit u te mogen verwelkomen.
    </p>
  </div>

  {#if formError}
    <div class="alert">
      <p><strong>Oeps!</strong> {formError}</p>
    </div>
  {/if}

  <form onsubmit={submitReservation} class="card reservation-form">
    <div class="form-section">
      <h3>Plan uw bezoek</h3>
      <div class="grid">
        <label>
          <span>Check-in</span>
          <input type="date" bind:value={booked_from} disabled={submitting} />
          {#if fieldErrors.booked_from}<small class="err"
              >{fieldErrors.booked_from}</small
            >{/if}
        </label>

        <label>
          <span>Check-out</span>
          <input
            type="date"
            bind:value={booked_till}
            min={booked_from || undefined}
            disabled={submitting}
          />
          {#if fieldErrors.booked_till}<small class="err"
              >{fieldErrors.booked_till}</small
            >{/if}
        </label>
      </div>
    </div>

    <div class="form-section">
      <h3>Betaling</h3>
      <label>
        <span>Betaalmethode</span>
        <input
          type="text"
          placeholder="Bijv. iDEAL, Creditcard of contant bij aankomst"
          bind:value={payment_method}
          disabled={submitting}
        />
        {#if fieldErrors.payment_method}<small class="err"
            >{fieldErrors.payment_method}</small
          >{/if}
      </label>
    </div>

    <div class="actions">
      <button
        type="button"
        class="btn secondary"
        onclick={() => goto("/rooms")}
        disabled={submitting}
      >
        Annuleren
      </button>
      <button type="submit" class="btn" disabled={submitting}>
        {submitting ? "Verwerken..." : "Bevestig Boeking"}
      </button>
    </div>
  </form>

  {#if created}
    <div class="card success-card">
      <div class="success-icon">✓</div>
      <h2>Reservering voltooid!</h2>
      <p>
        Uw verblijf is vastgelegd. Reservering <strong>#{created.id}</strong>
        voor kamer <strong>#{created.room_id}</strong> is succesvol aangemaakt.
      </p>
      <small>U wordt nu teruggestuurd naar het overzicht...</small>
    </div>
  {/if}
</section>
