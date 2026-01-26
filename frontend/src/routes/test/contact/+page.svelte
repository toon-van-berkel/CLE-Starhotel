<script lang="ts">
  import type { ContactListResponse } from "$lib/api/client/apiTypes";
  export let data: { contactsData: ContactListResponse };
</script>

{#if data.contactsData.error}
  <p>{data.contactsData.error}</p>
{:else}
  {#each data.contactsData.records as c}
    <a href={`/test/contact/${c.id}`}>
      #{c.id} — {c.title} ({c.email})
    </a>
    <br />
  {/each}
{/if}

<a href="/test/contact/post">POST</a>