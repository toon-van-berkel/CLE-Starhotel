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
    if (!form.reason) fieldErrors.reason = "Reason is required";

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

<input type="text" bind:value={form.name} placeholder="Name" />

<input type="email" bind:value={form.email} placeholder="Email" />

<div class="field">
  <select id="reason" bind:value={form.reason}>
    <option value="" disabled>Select a reason</option>
    <option value="General question">General question</option>
    <option value="Support">Support</option>
    <option value="Bug report">Bug report</option>
    <option value="Feature request">Feature request</option>
    <option value="Other">Other</option>
  </select>
  {#if fieldErrors.reason}<div class="err">{fieldErrors.reason}</div>{/if}
</div>
<input type="text" bind:value={form.title} placeholder="Title" />

<!-- svelte-ignore element_invalid_self_closing_tag -->
<textarea bind:value={form.message} placeholder="Message" />

<input type="button" value="submit" onclick={onSubmit} />
