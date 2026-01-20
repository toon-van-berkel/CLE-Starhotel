<script lang="ts">
  import { sendContact } from "$lib/api/contact/contactSend";

  let name = "";
  let result: { ok: true; id?: number } | null = null;

  async function onSubmit() {
    result = await sendContact({ name });
    console.log(result);
  }
</script>

<input type="text" bind:value={name} />

<input type="button" value="submit" onclick={onSubmit} />
