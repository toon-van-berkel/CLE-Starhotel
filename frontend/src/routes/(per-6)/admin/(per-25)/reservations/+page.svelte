<script lang="ts">
  import "../../../../../scss/style.scss";
  import type { ReservationsListResponse } from "$lib/api/types/reservation";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";

  export let data: { reservationsData: ReservationsListResponse };
  let reservations = data.reservationsData;

  function formatDateTime(v: string | null | undefined) {
    if (!v) return "";
    return v.replace("T", " ").replace(".000Z", "");
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations"]}
  links={["/admin", "/admin/reservations"]}
/>

<section class="admin-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Reservations</h1>
      <p class="muted">Overview of all reservations.</p>
    </div>

    <div class="headActions">
      <a class="btn primary" href="/admin/reservations/create">POST</a>
    </div>
  </div>

  {#if reservations.error}
    <div class="alert">{reservations.error}</div>
  {:else}
    <div class="tableWrap">
      <table class="adminTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Room</th>
            <th>Booked at</th>
            <th>Booked from</th>
            <th>Booked till</th>
            <th>Status</th>
            <th>Check-in Details</th>
            <th>Payment</th>
            <th
              class="actionsCol"
              style="text-align: right; padding-right: 2rem;">Actions</th
            >
          </tr>
        </thead>

        <tbody>
          {#each reservations.records as reservation}
            <tr>
              <td class="mono bold">{reservation.id}</td>

              <td>
                <a
                  class="bold"
                  style="text-decoration: none; color: inherit;"
                  href={`/admin/users/user-${reservation.user?.id ?? reservation.user_id}`}
                >
                  User {reservation.user?.id ?? reservation.user_id}
                </a>
              </td>

              <td>
                <a
                  class="bold"
                  style="text-decoration: none; color: inherit;"
                  href={`/admin/rooms/room-${reservation.room_id}`}
                >
                  Room {reservation.room_id}
                </a>
              </td>

              <td class="mono">{formatDateTime(reservation.booked_at)}</td>
              <td class="mono">{reservation.booked_from}</td>
              <td class="mono">{reservation.booked_till}</td>

              <td>
                {#if reservation.checked_in === 1}
                  <span class="badge ok">In</span>
                {:else}
                  <span class="badge">Out</span>
                {/if}
                <span class="badge neutral">#{reservation.status_id}</span>
              </td>

              <td class="mono" style="font-size: 0.8rem;">
                <div style="color: rgba(0,0,0,0.4)">
                  IN: {reservation.checked_in_at
                    ? formatDateTime(reservation.checked_in_at)
                    : "—"}
                </div>
                <div style="color: rgba(0,0,0,0.4)">
                  OUT: {reservation.checked_out_at
                    ? formatDateTime(reservation.checked_out_at)
                    : "—"}
                </div>
              </td>

              <td><span class="badge">{reservation.payment_method}</span></td>

              <td
                class="actions"
                style="text-align: right; padding-right: 1rem;"
              >
                <a
                  class="btn small"
                  href={`/admin/reservations/reservation-${reservation.id}`}
                  >View</a
                >
                <a
                  class="btn small"
                  href={`/admin/reservations/update-${reservation.id}`}>Edit</a
                >
                <a
                  class="btn small danger"
                  href={`/admin/reservations/cancel-${reservation.id}`}
                  >Cancel</a
                >
                <a
                  class="btn small danger"
                  href={`/admin/reservations/delete-${reservation.id}`}
                  >Delete</a
                >
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
    </div>
  {/if}
</section>
