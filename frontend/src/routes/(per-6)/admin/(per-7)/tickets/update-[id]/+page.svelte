<script lang="ts">
  import "../../../../../../scss/style.scss";
  import { goto } from "$app/navigation";
  import { apiSubmit } from "$lib/api/client/__index__";
  import type {
    ContactRecordResponse,
    ContactUpdateResponse,
  } from "$lib/api/types/contact";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";

  export let data: { id: number; contactData: ContactRecordResponse };
  const ticket = data.contactData.record;

  let title = ticket?.title ?? "";
  let name = ticket?.name ?? "";
  let email = ticket?.email ?? "";
  let reason = ticket?.reason ?? "";
  let message = ticket?.message ?? "";
  let status_id = ticket?.status_id ?? 50;

  let saving = false;
  let error = "";
  let fieldErrors: Record<string, string> = {};

  async function save() {
    if (!ticket) return;
    saving = true;
    error = "";
    fieldErrors = {};

    try {
      const res: ContactUpdateResponse = await apiSubmit(
        "contactUpdate",
        fetch,
        {
          id: data.id,
          title,
          name,
          email,
          reason,
          message,
          status_id,
        },
      );

      if (!res.ok) {
        fieldErrors = res.fields ?? {};
        throw new Error(res.error ?? "Update mislukt");
      }
      await goto(`/admin/tickets/ticket-${data.id}`);
    } catch (e: any) {
      if (!Object.keys(fieldErrors).length)
        error = e?.message ?? "Update mislukt";
    } finally {
      saving = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Tickets", `Update #${ticket?.id}`]}
  links={["/admin", "/admin/tickets", `/admin/tickets/update-${ticket?.id}`]}
/>

<section class="admin-detail-page">
  {#if ticket}
    <div class="pageHead">
      <div class="header-content">
        <h1>Ticket Bijwerken</h1>
        <p class="muted">Aanpassen van ticket #{ticket.id}: {ticket.title}</p>
      </div>
      <div class="headActions">
        <a class="btn" href={`/admin/tickets/ticket-${data.id}`}
          >Terug naar Ticket</a
        >
      </div>
    </div>

    {#if error}
      <div class="alert"><p><strong>Fout:</strong> {error}</p></div>
    {/if}

    <form
      class="admin-card"
      onsubmit={(e) => {
        e.preventDefault();
        save();
      }}
    >
      <div class="detail-section">
        <h3>Afzender & Onderwerp</h3>
        <div class="grid">
          <label>
            <span>Naam</span>
            <input bind:value={name} disabled={saving} />
            {#if fieldErrors.name}<small class="err">{fieldErrors.name}</small
              >{/if}
          </label>
          <label>
            <span>Email Adres</span>
            <input type="email" bind:value={email} disabled={saving} />
            {#if fieldErrors.email}<small class="err">{fieldErrors.email}</small
              >{/if}
          </label>
          <label>
            <span>Onderwerp / Titel</span>
            <input bind:value={title} disabled={saving} />
            {#if fieldErrors.title}<small class="err">{fieldErrors.title}</small
              >{/if}
          </label>
        </div>
      </div>

      <div class="detail-section">
        <h3>Classificatie & Status</h3>
        <div class="grid">
          <label>
            <span>Reden / Categorie</span>
            <input bind:value={reason} disabled={saving} />
            {#if fieldErrors.reason}<small class="err"
                >{fieldErrors.reason}</small
              >{/if}
          </label>
          <label>
            <span>Ticket Status</span>
            <select bind:value={status_id} disabled={saving}>
              <option value={1}>Nieuw</option>
              <option value={2}>In behandeling</option>
              <option value={3}>Afgehandeld</option>
            </select>
          </label>
        </div>
      </div>

      <div class="detail-section message-bg">
        <h3>Inhoud van het bericht</h3>
        <label>
          <span>Bericht Tekst</span>
          <textarea
            rows="8"
            class="message-body"
            bind:value={message}
            disabled={saving}
          ></textarea>
          {#if fieldErrors.message}<small class="err"
              >{fieldErrors.message}</small
            >{/if}
        </label>
      </div>

      <div class="form-actions">
        <button
          type="button"
          class="btn"
          onclick={() => goto(`/admin/tickets/ticket-${data.id}`)}
          disabled={saving}
        >
          Annuleren
        </button>
        <button type="submit" class="btn primary" disabled={saving}>
          {saving ? "Bezig met opslaan..." : "Wijzigingen Opslaan"}
        </button>
      </div>
    </form>
  {:else}
    <div class="alert">Ticket niet gevonden.</div>
  {/if}
</section>
