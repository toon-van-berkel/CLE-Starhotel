<script lang="ts">
  import "../../../../../../scss/style.scss";
  import { goto } from "$app/navigation";
  import { apiSubmit } from "$lib/api/client/__index__";
  import type {
    ContactRecordResponse,
    ContactDeleteResponse,
  } from "$lib/api/client/apiTypes";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";

  export let data: { id: number; contactData: ContactRecordResponse };
  const ticket = data.contactData.record;

  let deleting = false;
  let error = "";

  async function doDelete() {
    deleting = true;
    error = "";
    try {
      const res: ContactDeleteResponse = await apiSubmit(
        "contactDelete",
        fetch,
        { id: data.id },
      );
      if (!res.ok) throw new Error(res.error ?? "Delete failed");
      await goto("/admin/tickets");
    } catch (e: any) {
      error = e?.message ?? "Delete failed";
    } finally {
      deleting = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Tickets", `Verwijder ticket #${ticket?.id}`]}
  links={["/admin", "/admin/tickets", `/admin/tickets/delete-${ticket?.id}`]}
/>

<section class="admin-detail-page">
  {#if ticket}
    <div class="pageHead">
      <div class="header-content">
        <h1>Ticket Verwijderen</h1>
        <p class="muted">
          U staat op het punt om ticket #{ticket.id} definitief te wissen.
        </p>
      </div>
      <div class="headActions">
        <a class="btn" href="/admin/tickets">Terug naar lijst</a>
      </div>
    </div>

    {#if error}
      <div class="alert"><p><strong>Fout:</strong> {error}</p></div>
    {/if}

    <div class="admin-card">
      <div class="detail-section">
        <h3 style="color: #d9534f; border-color: rgba(217, 83, 79, 0.1);">
          Waarschuwing
        </h3>
        <p
          style="font-size: 1.2rem; color: #d9534f; font-weight: 600; margin-bottom: 2rem;"
        >
          Weet u zeker dat u dit ticket definitief wilt verwijderen? Deze actie
          kan niet ongedaan worden gemaakt.
        </p>

        <div class="detail-grid">
          <div class="item">
            <span>Onderwerp</span>
            <p>{ticket.title}</p>
          </div>
          <div class="item">
            <span>Afzender</span>
            <p>{ticket.name}</p>
          </div>
          <div class="item">
            <span>Datum</span>
            <p class="mono">{ticket.created_at.split("T")[0]}</p>
          </div>
        </div>
      </div>

      <div
        class="form-actions"
        style="border-top: 1px solid rgba(217, 83, 79, 0.1);"
      >
        <button
          class="btn"
          onclick={() => goto(`/admin/tickets/ticket-${ticket.id}`)}
          disabled={deleting}
        >
          Annuleren
        </button>
        <button class="btn danger" onclick={doDelete} disabled={deleting}>
          {deleting ? "Bezig met verwijderen..." : "Definitief Verwijderen"}
        </button>
      </div>
    </div>
  {:else}
    <div class="admin-card">
      <p>Ticket niet gevonden.</p>
      <div class="form-actions">
        <a class="btn primary" href="/admin/tickets">Terug naar overzicht</a>
      </div>
    </div>
  {/if}
</section>
