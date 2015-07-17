<?php $this->load->view('header', array("page" => "/part/partlist")); ?>
<ol class="breadcrumb">
  <li><a href="/part/partlist">Parts</a></li>
  <li class="active">
    <a href="/part/detail/<?php echo $part->getId(); ?>">
      <?php echo $part->getName(); ?>
    </a>
  </li>
</ol>
<div class="jumbotron">
  <h1><?php echo $part->getName(); ?></h1>
  <p>
    <ul>
      <li><label>Car:</label> <?php echo $part->getCar()->getName(); ?></li>
      <li><label>Price:</label> <?php echo $part->getPrice() ?></li>
    </ul>
  </p>
</div>
