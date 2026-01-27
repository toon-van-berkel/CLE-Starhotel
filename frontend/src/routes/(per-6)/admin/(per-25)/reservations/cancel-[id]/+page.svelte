<script lang="ts">
  import "../../../../../../scss/style.scss";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  import { goto } from "$app/navigation";
  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import type { ApiError } from "$lib/api/client/apiBase";

  export let data: { id: number; reservationData: any };

  const r = data.reservationData?.record ?? null;

  let submitting = false;
  let error = "";

  async function confirmCancel() {
    error = "";
    submitting = true;

    try {
      const res = await apiSubmit("reservationCancel", fetch, { id: data.id });
      if (res?.ok === true) {
        await goto("/admin/reservations");
        return;
      }
      error = res?.error ?? "Cancel failed";
    } catch (err) {
      const e = err as ApiError;
      const d = (e.data ?? {}) as any;
      error = d.error ?? e.message ?? "Cancel failed";
    } finally {
      submitting = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations", `Cancel #${data.id}`]}
  links={[
    "/admin",
    "/admin/reservations",
    `/admin/reservations/cancel-${data.id}`,
  ]}
/>

<section class="admin-detail-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Cancel reservation</h1>
      <p class="muted">Reservation #{data.id}</p>
    </div>
    <div class="headActions">
      <a class="btn" href="/admin/reservations">Back to list</a>
    </div>
  </div>

  {#if data.reservationData?.error}
    <div class="alert">{data.reservationData.error}</div>
  {:else if !r}
    <div class="alert">Reservation not found.</div>
  {:else}
    {#if error}
      <div class="alert">{error}</div>
    {/if}

    <div class="admin-card">
      <div class="detail-section">
        <h3 style="color: #d9534f;">Confirm Cancellation</h3>
        <p>
          Are you sure you want to <strong>cancel</strong> this reservation? This
          action will update the status to cancelled.
        </p>
      </div>

      <div class="detail-section">
        <h3>Reservation Summary</h3>
        <div class="detail-grid">
          <div class="item">
            <span>User ID</span>
            <p>{r.user_id}</p>
          </div>
          <div class="item">
            <span>Room ID</span>
            <p>{r.room_id}</p>
          </div>
          <div class="item">
            <span>Payment</span>
            <p>{r.payment_method}</p>
          </div>
          <div class="item">
            <span>From</span>
            <p class="mono">{r.booked_from}</p>
          </div>
          <div class="item">
            <span>Till</span>
            <p class="mono">{r.booked_till}</p>
          </div>
          <div class="item">
            <span>Current Status</span>
            <p><span class="badge">#{r.status_id}</span></p>
          </div>
        </div>
      </div>

      <div
        class="form-actions"
        style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 2rem;"
      >
        <button
          class="btn"
          type="button"
          onclick={() => goto("/admin/reservations")}
          disabled={submitting}
        >
          No, keep reservation
        </button>
        <button
          class="btn danger primary"
          type="button"
          onclick={confirmCancel}
          disabled={submitting}
        >
          {submitting ? "Cancelling…" : "Confirm cancellation"}
        </button>
      </div>
    </div>
  {/if}
</section>
