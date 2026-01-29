<script lang="ts">
  import "../../scss/style.scss";
  import { sendContact } from "$lib/api/contact/contactSend";

  let form = {
    name: "",
    email: "",
    reason: "",
    title: "",
    message: "",
  };
  let fieldErrors: Record<string, string> = {};
  let result: { ok: true; id?: number } | null = null;

  async function onSubmit() {
    fieldErrors = {};
    if (!form.name) fieldErrors.name = "Name is required";
    if (!form.email) fieldErrors.email = "Email is required";
    if (!form.reason) fieldErrors.reason = "Reason is required";
    if (!form.title) fieldErrors.title = "Title is required";
    if (!form.message) fieldErrors.message = "Message is required";

    if (Object.keys(fieldErrors).length > 0) return;

    try {
      result = await sendContact({
        name: form.name,
        email: form.email,
        reason: form.reason,
        title: form.title,
        message: form.message,
        created_at: new Date().toISOString(),
      });
    } catch (error) {
      console.error("Submission failed:", error);
    }
  }
</script>

<div class="contact-page">
  <div class="contact-header">
    <span class="subtitle">Neem contact op</span>
    <h1>Hoe kunnne wij u helpen?</h1>
    <p>Hoe kunnen wij u helpen om uw verblijf onvergetelijk te maken?</p>
  </div>

  <div class="contact-container">
    {#if result?.ok}
      <div class="success-message">
        <h2>Bedankt, {form.name}</h2>
        <p>
          Uw bericht is verzonden. Ons team neemt zo spoedig mogelijk contact
          met u op.
        </p>
        <button on:click={() => (result = null)} class="btn-primary"
          >Stuur nog een bericht</button
        >
      </div>
    {:else}
      <div class="contact-form">
        <div class="form-row">
          <div class="form-group">
            <input
              type="text"
              class:invalid={fieldErrors.name}
              bind:value={form.name}
              placeholder="Volledige Naam"
            />
            {#if fieldErrors.name}<span class="err">{fieldErrors.name}</span
              >{/if}
          </div>
          <div class="form-group">
            <input
              type="email"
              class:invalid={fieldErrors.email}
              bind:value={form.email}
              placeholder="E-mailadres"
            />
            {#if fieldErrors.email}<span class="err">{fieldErrors.email}</span
              >{/if}
          </div>
        </div>

        <div class="form-group">
          <select class:invalid={fieldErrors.reason} bind:value={form.reason}>
            <option value="" disabled selected>Rede voor contact</option>
            <option value="Algemene vraag">Algemene vraag</option>
            <option value="Support">Support</option>
            <option value="Bug melding">Bug melding</option>
            <option value="Ideeverzoek">Feature request</option>
            <option value="Anders">Anders</option>
          </select>
          {#if fieldErrors.reason}<span class="err">{fieldErrors.reason}</span
            >{/if}
        </div>

        <div class="form-group">
          <input
            type="text"
            class:invalid={fieldErrors.title}
            bind:value={form.title}
            placeholder="Subject Title"
          />
          {#if fieldErrors.title}<span class="err">{fieldErrors.title}</span
            >{/if}
        </div>

        <div class="form-group">
          <textarea
            class:invalid={fieldErrors.message}
            bind:value={form.message}
            placeholder="Hoe kunnen wij u helpen?"
          ></textarea>
          {#if fieldErrors.message}<span class="err">{fieldErrors.message}</span
            >{/if}
        </div>

        <button class="form-button" on:click={onSubmit}>Verstuur Bericht</button
        >
      </div>
    {/if}
  </div>
</div>
