<script lang="ts">
    import { apiSubmit } from '$lib/api/client/__index__';
    import type { ContactInput } from '$lib/api/types/contact';
    import type {ContactResponse} from '$lib/api/client/apiTypes';
    import Breadcrumbs from '$lib/components/Breadcrumbs.svelte';

    let form: ContactInput = {
        name: '',
        email: '',
        reason: '',
        title: '',
        message: '',
        user_id: null
    };

    let loading = false;
    let result: ContactResponse | null = null;

    // field-level errors from backend validation
    let fieldErrors: Record<string, string> = {};
    let generalError = '';

    async function onSubmit() {
        loading = true;
        result = null;
        generalError = '';
        fieldErrors = {};

        try {
            // IMPORTANT: use event fetch from load() only if you pass it in.
            // On client-side pages, global fetch works fine for same-origin.
            const res = await apiSubmit('contact', fetch, form);

            result = res;

            if (res.ok) {
                // reset form on success
                form = {
                    name: '',
                    email: '',
                    reason: '',
                    title: '',
                    message: '',
                    user_id: null
                };
            }
        } catch (e) {
            // If your api() helper throws with parsed JSON in message, you can improve this,
            // but this is safe fallback.
            generalError = (e as Error).message || 'Something went wrong';
        } finally {
            loading = false;
        }
    }
</script>

<Breadcrumbs
	names={['Admin Panel', 'tickets', 'create']}
	links={['/admin', '/admin/tickets', 'create']}
/>

<div class="wrap">
    <h1>Create ticket</h1>
    <p class="sub">Send a message to the team.</p>

    <form class="card" on:submit|preventDefault={onSubmit}>
        <div class="grid">
        <div class="field">
            <label for="name">Name</label>
            <input id="name" type="text" bind:value={form.name} placeholder="Your name" autocomplete="name"/>
            {#if fieldErrors.name}<div class="err">{fieldErrors.name}</div>{/if}
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" bind:value={form.email} placeholder="you@example.com" autocomplete="email" />
            {#if fieldErrors.email}<div class="err">{fieldErrors.email}</div>{/if}
        </div>

        <div class="field">
            <label for="reason">Reason</label>
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

        <div class="field">
            <label for="title">Title</label>
            <input id="title" type="text" bind:value={form.title} placeholder="Short title" />
            {#if fieldErrors.title}<div class="err">{fieldErrors.title}</div>{/if}
        </div>

        <div class="field full">
            <label for="message">Message</label>
            <textarea id="message" rows="6" bind:value={form.message} placeholder="Write your message..." ></textarea>
            {#if fieldErrors.message}<div class="err">{fieldErrors.message}</div>{/if}
        </div>
        </div>

        {#if generalError}
            <div class="banner bad">{generalError}</div>
        {/if}

        {#if result && !result.ok}
            <div class="banner bad">
                {result.error}
            </div>

            {#if result.fields}
                {#each Object.entries(result.fields) as [k, v]}
                    <div class="minierr"><b>{k}</b>: {v}</div>
                {/each}
            {/if}
        {/if}

        {#if result?.ok}
            <div class="banner good">
                Sent! Ticket id: {result.id}
            </div>
        {/if}

        <button class="btn" type="submit" disabled={loading}>
            {#if loading}Sending...{:else}Send message{/if}
        </button>
    </form>
</div>