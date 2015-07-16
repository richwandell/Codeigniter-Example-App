<?php $this->load->view('header', array("page" => "/passenger/passengerlist")); ?>
<div class="panel panel-default" id="passenger_list">
  <table class="table table-striped table-hover">
    <caption>
      All Passengers in All Cars <br>
      <div id="flash_message"></div>
      <div id="flash_error"></div>
    </caption>
    <thead>
    <tr>
      <th>Name</th>
      <th>Car</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($passengers as $passenger): ?>
    <tr>
      <td>
        <a href="/passenger/detail/<?php echo $passenger->getId(); ?>">
          <?php echo $passenger->getFirstName() . " " . $passenger->getLastName(); ?>
        </a>
      </td>
      <td>
        <?php if($passenger->getCar()): ?>
        <a href="/car/detail/<?php echo $passenger->getCar()->getId(); ?>">
          <?php echo $passenger->getCar()->getName(); ?>
        </a>
        <?php endif; ?>
      </td>
      <td>
        <form action="/passenger/deletePassenger" method="POST">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="passenger_id" value="<?php echo $passenger->getId(); ?>"/>
          <input class="btn btn-danger" type="submit" value="Delete" />
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>