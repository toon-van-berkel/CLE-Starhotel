<script lang="ts">
  import "../../../../../scss/style.scss";
  import type { ContactListResponse } from "$lib/api/client/apiTypes";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";
  export let data: { contactsData: ContactListResponse };
</script>

<Breadcrumbs
  names={["Admin Panel", "Tickets"]}
  links={["/admin", "/admin/tickets"]}
/>

<section class="admin-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Support Tickets</h1>
      <p class="muted">
        Berichten en vragen ontvangen via het contactformulier.
      </p>
    </div>
    <div class="headActions">
      <a class="btn primary" href="/admin/tickets/create">Nieuw Ticket</a>
    </div>
  </div>

  {#if data.contactsData.error}
    <div class="alert">
      <p><strong>Fout:</strong> {data.contactsData.error}</p>
    </div>
  {:else}
    <div class="tableWrap">
      <table class="adminTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Onderwerp</th>
            <th>Titel</th>
            <th>Bericht</th>
            <th>Afzender</th>
            <th>Datum</th>
            <th class="actionsCol" style="text-align: right;">Acties</th>
          </tr>
        </thead>
        <tbody>
          {#each data.contactsData.records as c}
            <tr>
              <td class="mono">#{c.id}</td>
              <td><span class="badge">{c.reason}</span></td>
              <td class="bold">{c.title}</td>

              <td class="message-cell">
                <div class="truncate">{c.message}</div>
              </td>

              <td>
                <div class="sender-info">
                  <span class="name">{c.name}</span>
                  <span class="email">{c.email}</span>
                </div>
              </td>

              <td class="mono small-date">{c.created_at.split("T")[0]}</td>

              <td class="actions">
                <a class="btn small" href={`/admin/tickets/ticket-${c.id}`}
                  >Bekijk</a
                >
                <a class="btn small" href={`/admin/tickets/update-${c.id}`}
                  >Bewerk</a
                >
                <a
                  class="btn small danger"
                  href={`/admin/tickets/delete-${c.id}`}>Verwijder</a
                >
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
    </div>
  {/if}
</section>
