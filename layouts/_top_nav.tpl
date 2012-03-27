<ul class="nav">
  {if $CUR_APP eq "dashboard"}
  <li class="active">
  {else}
  <li>
  {/if}
    <a href="http://{$smarty.server.HTTP_HOST}/{$DIR_NAME}/apps/dashboard/" >Dashboard</a>
  </li>
</ul>