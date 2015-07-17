<?php $this->load->view('header', array("page" => "/car/carlist")); ?>
<ol class="breadcrumb">
  <li><a href="/car/carlist">Cars</a></li>
  <li class="active">
    <a href="/car/detail/<?php echo $car->getId(); ?>">
      <?php echo $car->getName(); ?>
    </a>
  </li>
</ol>
<div class="jumbotron">
  <h1><?php echo $car->getName(); ?></h1>
  <p>
    <ul>
      <li><label>Number of Passengers:</label> <?php echo count($car->getPassengers()); ?></li>
      <li><label>Number of Parts:</label><?php echo count($car->getParts()); ?></li>
    </ul>
  </p>
</div>
