<script lang="ts">
  import "../../../../../../scss/style.scss";
  import type { ContactRecordResponse } from "$lib/api/client/apiTypes";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  export let data: { contactData: ContactRecordResponse };

  const ticket = data.contactData.record;
</script>

<Breadcrumbs
  names={["Admin Panel", "Tickets", `Ticket #${ticket?.id}`]}
  links={["/admin", "/admin/tickets", `/admin/tickets/ticket-${ticket?.id}`]}
/>

<section class="admin-detail-page">
  {#if ticket}
    <div class="pageHead">
      <div class="header-content">
        <h1>Ticket #{ticket.id}</h1>
        <p class="muted">Onderwerp: {ticket.title}</p>
      </div>
      <div class="headActions">
        <a class="btn primary" href={`/admin/tickets/update-${ticket.id}`}
          >Status Bijwerken</a
        >
      </div>
    </div>

    <div class="admin-card">
      <div class="detail-section">
        <h3>Klantinformatie</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Naam</span>
            <p>{ticket.name}</p>
          </div>
          <div class="item">
            <span>Email</span>
            <p class="accent">{ticket.email}</p>
          </div>
          <div class="item">
            <span>Gekoppelde Gebruiker</span>
            <p>
              {#if ticket.user_id}
                <a
                  class="link bold"
                  href={`/admin/users/user-${ticket.user_id}`}
                  >User #{ticket.user_id}</a
                >
              {:else}
                <span class="muted">Anoniem</span>
              {/if}
            </p>
          </div>
        </div>
      </div>

      <div class="detail-section message-bg">
        <h3>Inhoud van het bericht</h3>
        <div class="item">
          <span>Reden</span>
          <p class="bold">{ticket.reason}</p>
        </div>
        <div class="item mt-1">
          <span>Bericht van de gast</span>
          <div class="message-body">{ticket.message}</div>
        </div>
      </div>

      <div class="detail-section">
        <h3>Behandeling</h3>
        <div class="detail-grid">
          <div class="item">
            <span>Status</span>
            <p>
              <span class="badge {ticket.status_id === 3 ? 'ok' : ''}">
                {ticket.status_id === 1
                  ? "Nieuw"
                  : ticket.status_id === 2
                    ? "In behandeling"
                    : "Afgehandeld"}
              </span>
            </p>
          </div>
          <div class="item">
            <span>Behandeld door</span>
            <p>
              {ticket.admin_handled_id
                ? `Admin #${ticket.admin_handled_id}`
                : "Nog niet toegewezen"}
            </p>
          </div>
          <div class="item">
            <span>Behandeld op</span>
            <p class="mono">{ticket.handled_at || "—"}</p>
          </div>
        </div>
      </div>
    </div>
  {:else}
    <div class="alert">Ticket niet gevonden.</div>
  {/if}
</section>
