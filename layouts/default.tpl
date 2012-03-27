<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>{$Title|default:"ECStore-administration"}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="{$PUBLIC}/js/jquery-1.7.2.min.js"></script>
    <script src="{$PUBLIC}/js/bootstrap.min.js"></script>
    <link href="{$PUBLIC}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$PUBLIC}/css/bootstrap-responsive.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="{$PUBLIC}/js/html5.js"></script>
    <![endif]-->
    <!--[if IE 6]>
      <script src="{$PUBLIC}/js/ie6.min.js"></script>
      <link href="{$PUBLIC}/css/ie6.min.css" rel="stylesheet">
    <![endif]-->
    <style>
			body {
				padding-top: 60px;
			}
    </style>
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="http://{$smarty.server.HTTP_HOST}/{$DIR_NAME}/">ECStore-Administration</a>
          <div class="nav-collapse">
            {include file="_top_nav.tpl"}
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            {include file="_nav.tpl"}
          </div>
        </div>
        <div class="span9">
          {block name=content}Empty{/block}
        </div>
      </div>
      <hr/>
      <footer>
        <p>
          Jasl, BUU, NAF v0.1
        </p>
      </footer>
    </div>
  </body>
</html>