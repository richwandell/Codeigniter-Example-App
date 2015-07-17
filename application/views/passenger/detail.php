<?php $this->load->view('header', array("page" => "/passenger/passengerlist")); ?>
<ol class="breadcrumb">
  <li><a href="/passenger/passengerlist">Passengers</a></li>
  <li class="active">
    <a href="/passenger/detail/<?php echo $passenger->getId(); ?>">
      <?php echo $passenger->getFirstName() . " " . $passenger->getLastName(); ?>
    </a>
  </li>
</ol>
<div class="jumbotron">
  <h1><?php echo $passenger->getFirstName() . " " . $passenger->getLastName(); ?></h1>
  <p>
    <ul>
      <li><label>Car:</label><?php echo $passenger->getCar()->getName(); ?></li>
    </ul>
  </p>
</div>
