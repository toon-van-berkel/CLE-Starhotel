<script lang="ts">
  import "../../scss/_contact.scss";

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

    if (Object.keys(fieldErrors).length > 0) {
      console.error("Validation failed", fieldErrors);
      return;
    }

    console.log("Submitting contact form with", form);
    try {
      result = await sendContact({
        name: form.name,
        email: form.email,
        reason: form.reason,
        title: form.title,
        message: form.message,
        created_at: new Date().toISOString(),
      });
      console.log("Submission result:", result);
    } catch (error) {
      console.error("Submission failed:", error);
    }
  }
</script>

<div class="contact-form">
  <h1>Contact Us</h1>
  {#if fieldErrors.name}<div class="err">{fieldErrors.name}</div>{/if}
  <input
    type="text"
    class="form-input"
    bind:value={form.name}
    placeholder="Name"
  />
  {#if fieldErrors.email}<div class="err">{fieldErrors.email}</div>{/if}

  <input
    type="email"
    class="form-input"
    bind:value={form.email}
    placeholder="Email"
  />
  {#if fieldErrors.reason}<div class="err">{fieldErrors.reason}</div>{/if}

  <div class="field">
    <select id="reason" class="form-select" bind:value={form.reason}>
      <option value="" disabled>Select a reason</option>
      <option value="General question">General question</option>
      <option value="Support">Support</option>
      <option value="Bug report">Bug report</option>
      <option value="Feature request">Feature request</option>
      <option value="Other">Other</option>
    </select>
  </div>
  {#if fieldErrors.title}<div class="err">{fieldErrors.title}</div>{/if}
  <input
    type="text"
    class="form-input"
    bind:value={form.title}
    placeholder="Title"
  />

  {#if fieldErrors.message}<div class="err">{fieldErrors.message}</div>{/if}
  <textarea
    class="form-textarea"
    bind:value={form.message}
    placeholder="Message"
  ></textarea>

  <input type="button" class="form-button" value="submit" onclick={onSubmit} />
</div>
