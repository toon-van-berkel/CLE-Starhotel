<script lang="ts">
  import { sendContact } from "$lib/api/contact/contactSend";

  let name = "";
  let email = "";
  let result: { ok: true; id?: number } | null = null;

  async function onSubmit() {
    result = await sendContact({ name, email });
    console.log(result);
  }
</script>

<input type="text" bind:value={name} />
<input type="email" bind:value={email} />

<input type="button" value="submit" onclick={onSubmit} />
