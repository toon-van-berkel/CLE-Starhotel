<script lang="ts">
  import "../../../../../../../scss/style.scss";
  import type { ReservationResponse } from "$lib/api/client/apiTypes";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  export let data: { reservationsData: ReservationResponse };

  const reservation = data.reservationsData.record;
</script>

<Breadcrumbs
  names={["Admin Panel", "Reservations", `Reservering #${reservation?.id}`]}
  links={[
    "/admin",
    "/admin/reservations",
    `/admin/reservations/reservation-${reservation?.id}`,
  ]}
/>

<section class="admin-detail-page">
  {#if reservation}
    <div class="pageHead">
      <div>
        <h1>Reservering #{reservation.id}</h1>
        <p class="muted">Volledig overzicht van de boekingsdetails.</p>
      </div>
      <div class="headActions">
        <a class="btn" href={`/admin/reservations/update-${reservation.id}`}
          >Bewerken</a
        >
      </div>
    </div>

    <div class="card detail-card">
      <div class="detail-section">
        <h3>Basis Informatie</h3>
        <div class="detail-grid">
          <div class="item">
            <span>ID</span>
            <p>{reservation.id}</p>
          </div>
          <div class="item">
            <span>Gast ID</span>
            <p>{reservation.user_id}</p>
          </div>
          <div class="item">
            <span>Kamer ID</span>
            <p>{reservation.room_id}</p>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <h3>Planning & Tijden</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Geboekt op</span>
            <p>{reservation.booked_at}</p>
          </div>
          <div class="item">
            <span>Aankomst</span>
            <p>{reservation.booked_from}</p>
          </div>
          <div class="item">
            <span>Vertrek</span>
            <p>{reservation.booked_till}</p>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <h3>Check-in Status</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Status</span>
            <p>
              <span class="badge {reservation.checked_in ? 'ok' : ''}">
                {reservation.checked_in ? "Ingecheckt" : "Niet ingecheckt"}
              </span>
            </p>
          </div>
          <div class="item">
            <span>Ingecheckt op</span>
            <p>{reservation.checked_in_at || "—"}</p>
          </div>
          <div class="item">
            <span>Uitgecheckt op</span>
            <p>{reservation.checked_out_at || "—"}</p>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <h3>Betaling & Administratie</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Betaalmethode</span>
            <p>{reservation.payment_method}</p>
          </div>
          <div class="item">
            <span>Status ID</span>
            <p>#{reservation.status_id}</p>
          </div>
        </div>
      </div>
    </div>
  {:else}
    <div class="alert">Reservering niet gevonden.</div>
  {/if}
</section>
