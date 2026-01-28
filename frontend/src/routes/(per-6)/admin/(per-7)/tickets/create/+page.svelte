<script lang="ts">
  import { apiSubmit } from "$lib/api/client/__index__";
  import type { ContactInput } from "$lib/api/types/contact";
  import type { ContactResponse } from "$lib/api/client/apiTypes";
  import Breadcrumbs from "$lib/components/Breadcrumbs.svelte";

  let form: ContactInput = {
    name: "",
    email: "",
    reason: "",
    title: "",
    message: "",
    user_id: null,
  };

  let loading = false;
  let result: ContactResponse | null = null;
  let fieldErrors: Record<string, string> = {};
  let generalError = "";

  async function onSubmit() {
    loading = true;
    result = null;
    generalError = "";
    fieldErrors = {};

    try {
      const res = await apiSubmit("contact", fetch, form);
      result = res;

      if (res.ok) {
        form = {
          name: "",
          email: "",
          reason: "",
          title: "",
          message: "",
          user_id: null,
        };
      } else if (res.fields) {
        fieldErrors = res.fields;
      }
    } catch (e) {
      generalError = (e as Error).message || "Something went wrong";
    } finally {
      loading = false;
    }
  }
</script>

<Breadcrumbs
  names={["Admin Panel", "Tickets", "Create"]}
  links={["/admin", "/admin/tickets", "/admin/tickets/create"]}
/>

<section class="admin-detail-page">
  <div class="pageHead">
    <div class="header-content">
      <h1>Create New Ticket</h1>
      <p class="muted">
        Handmatig een nieuw support ticket aanmaken in het systeem.
      </p>
    </div>
    <div class="headActions">
      <a class="btn" href="/admin/tickets">Back to list</a>
    </div>
  </div>

  {#if generalError || (result && !result.ok)}
    <div class="alert">
      <p>
        <strong>Fout:</strong>
        {generalError || result?.error || "Controleer de velden."}
      </p>
    </div>
  {/if}

  {#if result?.ok}
    <div
      class="badge ok"
      style="display: block; padding: 1.5rem; margin-bottom: 2rem; font-size: 1rem;"
    >
      ✅ Ticket succesvol aangemaakt! ID: #{result.id}
    </div>
  {/if}

  <form class="admin-card" on:submit|preventDefault={onSubmit}>
    <div class="detail-section">
      <h3>Afzender Informatie</h3>
      <div class="grid">
        <label>
          <span>Volledige Naam</span>
          <input
            type="text"
            bind:value={form.name}
            placeholder="Naam van de gast"
            disabled={loading}
          />
          {#if fieldErrors.name}<small class="err">{fieldErrors.name}</small
            >{/if}
        </label>
        <label>
          <span>Email Adres</span>
          <input
            type="email"
            bind:value={form.email}
            placeholder="gast@voorbeeld.nl"
            disabled={loading}
          />
          {#if fieldErrors.email}<small class="err">{fieldErrors.email}</small
            >{/if}
        </label>
      </div>
    </div>

    <div class="detail-section">
      <h3>Ticket Details</h3>
      <div class="grid">
        <label>
          <span>Reden van contact</span>
          <select bind:value={form.reason} disabled={loading}>
            <option value="" disabled>Selecteer een reden</option>
            <option value="General question">General question</option>
            <option value="Support">Support</option>
            <option value="Bug report">Bug report</option>
            <option value="Feature request">Feature request</option>
            <option value="Other">Other</option>
          </select>
          {#if fieldErrors.reason}<small class="err">{fieldErrors.reason}</small
            >{/if}
        </label>
        <label>
          <span>Onderwerp</span>
          <input
            type="text"
            bind:value={form.title}
            placeholder="Korte titel"
            disabled={loading}
          />
          {#if fieldErrors.title}<small class="err">{fieldErrors.title}</small
            >{/if}
        </label>
      </div>
    </div>

    <div class="detail-section">
      <h3>Berichtinhoud</h3>
      <label>
        <span>Bericht</span>
        <textarea
          rows="8"
          bind:value={form.message}
          placeholder="Omschrijf het probleem of de vraag..."
          disabled={loading}
        ></textarea>
        {#if fieldErrors.message}<small class="err">{fieldErrors.message}</small
          >{/if}
      </label>
    </div>

    <div class="form-actions">
      <a class="btn" href="/admin/tickets">Annuleren</a>
      <button class="btn primary" type="submit" disabled={loading}>
        {loading ? "Bezig met verzenden..." : "Ticket Aanmaken"}
      </button>
    </div>
  </form>
</section>
