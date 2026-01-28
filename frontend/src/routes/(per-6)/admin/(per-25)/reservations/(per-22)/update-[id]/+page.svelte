<script lang="ts">
  import "../../../../../../../scss/style.scss";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  import { goto } from "$app/navigation";

  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import type { ApiError } from "$lib/api/client/apiBase";

  export let data: {
    id: number;
    reservationData: any;
    roomsData: any;
  };

  const reservation = data.reservationData?.record ?? null;
  const rooms = data.roomsData?.records ?? [];

  // form fields (keep room as string because <select> gives strings)
  let room_id = reservation ? String(reservation.room_id) : "";
  let booked_from = reservation?.booked_from ?? "";
  let booked_till = reservation?.booked_till ?? "";
  let payment_method = reservation?.payment_method ?? "";

  let submitting = false;
  let formError = "";
  let fieldErrors: Record<string, string> = {};

  function resetErrors() {
    formError = "";
    fieldErrors = {};
  }

  function clientValidate() {
    const fe: Record<string, string> = {};

    if (!room_id) fe.room_id = "Choose a room";
    if (!booked_from) fe.booked_from = "Choose a start date";
    if (!booked_till) fe.booked_till = "Choose an end date";
    if (booked_from && booked_till && booked_till < booked_from) {
      fe.booked_till = "End date must be after start date";
    }
    if (!payment_method.trim())
      fe.payment_method = "Payment method is required";

    fieldErrors = fe;
    return Object.keys(fe).length === 0;
  }

  async function submitUpdate(e: SubmitEvent) {
    e.preventDefault();
    resetErrors();
    if (!clientValidate()) return;

    submitting = true;
    try {
      const res = await apiSubmit("reservationUpdate", fetch, {
        id: data.id,
        room_id: Number(room_id),
        booked_from,
        booked_till,
        payment_method,
      });

      if (res?.ok === true) {
        await goto("/admin/reservations");
        return;
      }

      formError = res?.error ?? "Update failed";
      fieldErrors = res?.fields ?? {};
    } catch (err) {
      const e = err as ApiError;
      const d = (e.data ?? {}) as any;

      formError = d.error ?? e.message ?? "Update failed";
      fieldErrors = d.fields ?? {};
    } finally {
      submitting = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations", `Update #${data.id}`]}
  links={[
    "/admin",
    "/admin/reservations",
    `/admin/reservations/update-${data.id}`,
  ]}
/>

<section class="admin-detail-page">
  {#if data.reservationData?.error}
    <div class="alert">{data.reservationData.error}</div>
  {:else if !reservation}
    <div class="alert">Reservation not found.</div>
  {:else}
    <div class="pageHead">
      <div class="header-content">
        <h1>Update reservation</h1>
        <p class="muted">Edit reservation #{data.id}</p>
      </div>

      <div class="headActions">
        <a class="btn" href="/admin/reservations">Cancel</a>
      </div>
    </div>

    {#if formError}
      <div class="alert">{formError}</div>
    {/if}

    <form class="admin-card" onsubmit={submitUpdate}>
      <div class="detail-section">
        <h3>Room Assignment</h3>
        <label>
          <span>Room Selection</span>
          <select bind:value={room_id} disabled={submitting}>
            <option value="" disabled selected>Select a room</option>
            {#each rooms as r}
              <option value={String(r.id)}>
                Room {r.number} — {r.location} — {r.wing} — floor {r.floor} — max
                {r.max_capacity}
              </option>
            {/each}
          </select>
          {#if fieldErrors.room_id}<small class="err"
              >{fieldErrors.room_id}</small
            >{/if}
        </label>
      </div>

      <div class="detail-section">
        <h3>Timeframe</h3>
        <div class="grid">
          <label>
            <span>Booked from</span>
            <input type="date" bind:value={booked_from} disabled={submitting} />
            {#if fieldErrors.booked_from}<small class="err"
                >{fieldErrors.booked_from}</small
              >{/if}
          </label>

          <label>
            <span>Booked till</span>
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
        <h3>Payment Information</h3>
        <label>
          <span>Payment method</span>
          <input
            type="text"
            bind:value={payment_method}
            placeholder="e.g. Credit Card"
            disabled={submitting}
          />
          {#if fieldErrors.payment_method}<small class="err"
              >{fieldErrors.payment_method}</small
            >{/if}
        </label>
      </div>

      <div
        class="form-actions"
        style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 2rem;"
      >
        <button
          type="button"
          class="btn"
          onclick={() => goto("/admin/reservations")}
          disabled={submitting}
        >
          Discard
        </button>
        <button type="submit" class="btn primary" disabled={submitting}>
          {submitting ? "Saving…" : "Save changes"}
        </button>
      </div>
    </form>
  {/if}
</section>
