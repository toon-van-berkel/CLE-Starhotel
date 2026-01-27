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
    <span class="subtitle">Get in Touch</span>
    <h1>Contact Us</h1>
    <p>How can we assist you in making your stay unforgettable?</p>
  </div>

  <div class="contact-container">
    {#if result?.ok}
      <div class="success-message">
        <h2>Thank you, {form.name}</h2>
        <p>Your message has been sent. Our team will contact you shortly.</p>
        <button on:click={() => (result = null)} class="btn-primary"
          >Send another</button
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
              placeholder="Full Name"
            />
            {#if fieldErrors.name}<span class="err">{fieldErrors.name}</span
              >{/if}
          </div>
          <div class="form-group">
            <input
              type="email"
              class:invalid={fieldErrors.email}
              bind:value={form.email}
              placeholder="Email Address"
            />
            {#if fieldErrors.email}<span class="err">{fieldErrors.email}</span
              >{/if}
          </div>
        </div>

        <div class="form-group">
          <select class:invalid={fieldErrors.reason} bind:value={form.reason}>
            <option value="" disabled selected>Reason for contact</option>
            <option value="General question">General question</option>
            <option value="Support">Support</option>
            <option value="Bug report">Bug report</option>
            <option value="Feature request">Feature request</option>
            <option value="Other">Other</option>
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
            placeholder="How can we help?"
          ></textarea>
          {#if fieldErrors.message}<span class="err">{fieldErrors.message}</span
            >{/if}
        </div>

        <button class="form-button" on:click={onSubmit}>Send Message</button>
      </div>
    {/if}
  </div>
</div>
