<script lang="ts">
  import "../../scss/style.scss";
  type BreadcrumbsProps = {
    names: string[];
    links?: string[];
    link?: string[];
    separator?: string;
    class?: string;
  };

  let {
    names,
    links,
    link,
    separator = "/",
    class: className = "",
  }: BreadcrumbsProps = $props();

  function getHref(index: number): string | undefined {
    const hrefList = links ?? link;
    return hrefList?.[index];
  }
</script>

<nav aria-label="Breadcrumb" class={`breadcrumbs ${className}`}>
  <ol class="breadcrumbs__list">
    {#each names as name, index (index)}
      {@const hrefValue = getHref(index)}
      {@const isLast = index === names.length - 1}

      <li class="breadcrumbs__item">
        {#if !isLast && hrefValue}
          <a class="breadcrumbs__link" href={hrefValue}>{name}</a>
        {:else if !isLast}
          <span class="breadcrumbs__text">{name}</span>
        {:else}
          <span class="breadcrumbs__current" aria-current="page">{name}</span>
        {/if}

        {#if !isLast}
          <span class="breadcrumbs__separator" aria-hidden="true"
            >{separator}</span
          >
        {/if}
      </li>
    {/each}
  </ol>
</nav>
