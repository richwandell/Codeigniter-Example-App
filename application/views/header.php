<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="/static/bootstrap.min.css" />
  <link rel="stylesheet" href="/static/style.css" />
  <script src="/static/jquery.js"></script>
  <script src="/static/all.js"></script>
</head>
<div class="page-header">
  <h1>Codeigniter Example App <small>by Rich Wandell</small></h1>
</div>
<ul class="nav nav-tabs">
  <?php foreach(array(
      "/car/carlist" => "Cars",
      "/part/partlist" => "Parts",
      "/passenger/passengerlist" => "Passengers"
  ) as $link => $label): ?>
    <li role="presentation" <?php echo $link == $page ? 'class="active"' : ""; ?>>
      <a href="<?php echo $link; ?>"><?php echo $label; ?></a>
    </li>
  <?php endforeach; ?>
</ul>