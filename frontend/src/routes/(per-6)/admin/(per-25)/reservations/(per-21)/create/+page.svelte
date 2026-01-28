<script lang="ts">
  import { goto } from "$app/navigation";
  import { apiCall } from "$lib/api/client/apiCall";
  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import type { ApiError } from "$lib/api/client/apiBase";
  import type { Room } from "$lib/api/types/room";
  import type { ReservationApiRecord } from "$lib/api/types/reservation";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";

  let rooms = $state<Room[]>([]);
  let loadingRooms = $state(true);

  // form fields
  let room_id = $state(0);
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
    if (!room_id) fe.room_id = "Kies een kamer";
    if (!booked_from) fe.booked_from = "Kies een startdatum";
    if (!booked_till) fe.booked_till = "Kies een einddatum";
    if (booked_from && booked_till && booked_till < booked_from) {
      fe.booked_till = "Einddatum moet na de startdatum liggen";
    }
    if (!payment_method.trim())
      fe.payment_method = "Betaalmethode is verplicht";
    fieldErrors = fe;
    return Object.keys(fe).length === 0;
  }

  async function loadRooms() {
    loadingRooms = true;
    try {
      const res = await apiCall("rooms", fetch);
      rooms = res.records ?? [];
    } catch (err) {
      const e = err as ApiError;
      formError = (e.data as any)?.error ?? e.message ?? "Kamers laden mislukt";
    } finally {
      loadingRooms = false;
    }
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
        await goto("/admin/reservations");
        return;
      }
      formError = (res as any)?.error ?? "Reservering mislukt";
      fieldErrors = (res as any)?.fields ?? {};
    } catch (err) {
      const e = err as ApiError;
      const data = (e.data ?? {}) as any;
      formError = data.error ?? e.message ?? "Reservering mislukt";
      fieldErrors = data.fields ?? {};
    } finally {
      submitting = false;
    }
  }

  let didInit = $state(false);
  $effect(() => {
    if (didInit) return;
    didInit = true;
    loadRooms();
  });
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations", "Create"]}
  links={["/admin", "/admin/reservations", "/admin/reservations/create"]}
/>

<section class="admin-detail-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Nieuwe Reservering</h1>
      <p class="muted">Maak handmatig een nieuwe boeking aan voor een gast.</p>
    </div>
    <div class="headActions">
      <a class="btn" href="/admin/reservations">Terug naar lijst</a>
    </div>
  </div>

  {#if formError}
    <div class="alert"><p><strong>Fout:</strong> {formError}</p></div>
  {/if}

  {#if created}
    <div
      class="badge ok"
      style="display: block; padding: 1.5rem; margin-bottom: 2rem; font-size: 1rem;"
    >
      ✅ Reservering #{created.id} succesvol aangemaakt!
    </div>
  {/if}

  <form onsubmit={submitReservation} class="admin-card">
    <div class="detail-section">
      <h3>Kamer Selectie</h3>
      <label>
        <span>Selecteer Kamer</span>
        <select bind:value={room_id} disabled={submitting || loadingRooms}>
          <option value={0} disabled selected>
            {loadingRooms ? "Kamers laden..." : "Kies een beschikbare kamer"}
          </option>
          {#each rooms as r}
            <option value={r.id}>
              Kamer {r.number} — {r.location} ({r.wing}) — Max {r.max_capacity} pers.
            </option>
          {/each}
        </select>
        {#if fieldErrors.room_id}<small class="err">{fieldErrors.room_id}</small
          >{/if}
      </label>
    </div>

    <div class="detail-section">
      <h3>Verblijfsduur</h3>
      <div class="grid">
        <label>
          <span>Incheckdatum</span>
          <input type="date" bind:value={booked_from} disabled={submitting} />
          {#if fieldErrors.booked_from}<small class="err"
              >{fieldErrors.booked_from}</small
            >{/if}
        </label>

        <label>
          <span>Uitcheckdatum</span>
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

    <div class="detail-section">
      <h3>Betalingsinformatie</h3>
      <label>
        <span>Betaalmethode</span>
        <input
          type="text"
          placeholder="bijv. Creditcard, Contant, iDEAL..."
          bind:value={payment_method}
          disabled={submitting}
        />
        {#if fieldErrors.payment_method}<small class="err"
            >{fieldErrors.payment_method}</small
          >{/if}
      </label>
    </div>

    <div class="form-actions">
      <button
        type="button"
        class="btn"
        onclick={() => goto("/admin/reservations")}
        disabled={submitting}
      >
        Annuleren
      </button>
      <button
        type="submit"
        class="btn primary"
        disabled={submitting || loadingRooms}
      >
        {submitting ? "Bezig met aanmaken..." : "Reservering Bevestigen"}
      </button>
    </div>
  </form>
</section>
