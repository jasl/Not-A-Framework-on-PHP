<ul class="nav nav-list">
{foreach from=$CATES item=cate}
  <li class="nav-header">{$cate.display}</li>
  {foreach from=$cate.apps item=app}
  {if $app.path eq $CUR_APP}
  <li class="active">
  {else}
  <li>
  {/if}
    <a href="http://{$smarty.server.HTTP_HOST}/{$DIR_NAME}/apps/{$app.path}">{$app.display}</a>
  </li>
  {/foreach}
{/foreach}
</ul>