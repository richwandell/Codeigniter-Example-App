<?php $this->load->view('header', array("page" => "/car/carlist")); ?>
<div class="jumbotron">
  <h1>The Car List</h1>
  <p>
    Add a car using the form on the bottom of the page.<br>
    Click the blue button in the "Passengers" column to add a passenger to your car<br>
    Click the blue button in the "Parts" column to add a part to your car.<br>
  </p>
</div>
<div class="panel panel-default" id="passenger_list">
  <table class="table table-striped table-hover">
    <caption>
      Cars! <br>
      <div id="flash_message"></div>
      <div id="flash_error"></div>
    </caption>
    <thead>
    <tr>
      <th>Car</th>
      <th>Passengers</th>
      <th>Parts</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cars as $car): ?>
    <tr>
      <td>
        <a href="/car/detail/<?php echo $car->getId(); ?>">
          <?php echo $car->getName(); ?>
        </a>
      </td>
      <td>
        <a href="/car/passengerList/<?php echo $car->getId(); ?>">
          <button class="btn btn-primary" type="button">
            <span class="badge"><?php echo count($car->getPassengers()); ?></span>
          </button>
        </a>
      </td>
      <td>
        <a href="/car/partList/<?php echo $car->getId(); ?>">
          <button class="btn btn-primary" type="button">
            <span class="badge"><?php echo count($car->getParts()); ?></span>
          </button>
        </a>
      </td>
      <td>
        <form action="/car/deleteCar" method="POST">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="">
          <input type="hidden" name="car_id" value="<?php echo $car->getId(); ?>"/>
          <input class="btn btn-danger" type="submit" value="Delete" />
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="panel panel-default" id="new_passenger">
  <form class="form-horizontal" action="/car/addCar" method="POST">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="">
    <div class="form-group" id="car_name_error">
      <label for="car_name" class="col-sm-2 control-label">Car Name</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="car_name_value" placeholder="Enter Car Name..." name="car_name">
      </div>
    </div>
    <input type="submit" class="btn btn-default" value="Add Car"/>
  </form>
</div>
