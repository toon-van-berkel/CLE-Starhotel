<script lang="ts">
  import "../../../../../../../scss/style.scss";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  import { goto } from "$app/navigation";
  import { apiSubmit } from "$lib/api/client/apiSubmit";
  import type { ApiError } from "$lib/api/client/apiBase";

  export let data: { id: number; reservationData: any };

  const r = data.reservationData?.record ?? null;

  let submitting = false;
  let error = "";

  async function confirmDelete() {
    error = "";
    submitting = true;

    try {
      const res = await apiSubmit("reservationDelete", fetch, { id: data.id });
      if (res?.ok === true) {
        await goto("/admin/reservations");
        return;
      }
      error = res?.error ?? "Delete failed";
    } catch (err) {
      const e = err as ApiError;
      const d = (e.data ?? {}) as any;
      error = d.error ?? e.message ?? "Delete failed";
    } finally {
      submitting = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations", `Delete #${data.id}`]}
  links={[
    "/admin",
    "/admin/reservations",
    `/admin/reservations/delete-${data.id}`,
  ]}
/>

<section class="admin-detail-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Delete reservation</h1>
      <p class="muted">Permanently remove reservation #{data.id}</p>
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
        <h3 style="color: #d9534f;">Critical Action</h3>
        <p>
          You are about to <strong>permanently delete</strong> this record. This action
          cannot be undone. Please review the reservation details below before proceeding.
        </p>
      </div>

      <div class="detail-section">
        <h3>Reservation Review</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Reservation ID</span>
            <p class="mono">#{r.id}</p>
          </div>
          <div class="item">
            <span>User</span>
            <p>User {r.user_id}</p>
          </div>
          <div class="item">
            <span>Room</span>
            <p>Room {r.room_id}</p>
          </div>
          <div class="item">
            <span>Stay Period</span>
            <p style="font-size: 0.9rem;">
              {r.booked_from} <br /> to {r.booked_till}
            </p>
          </div>
          <div class="item">
            <span>Payment</span>
            <p>{r.payment_method}</p>
          </div>
          <div class="item">
            <span>Status</span>
            <p><span class="badge neutral">#{r.status_id}</span></p>
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
          No, keep record
        </button>
        <button
          class="btn danger primary"
          type="button"
          onclick={confirmDelete}
          disabled={submitting}
        >
          {submitting ? "Deleting…" : "Delete Permanently"}
        </button>
      </div>
    </div>
  {/if}
</section>
