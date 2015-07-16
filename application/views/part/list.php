<?php $this->load->view('header', array("page" => "/part/partlist")); ?>
<div class="panel panel-default" id="passenger_list">
  <table class="table table-striped table-hover">
    <caption>
      All Parts in All Cars <br>
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
    <?php foreach($parts as $part): ?>
    <tr>
      <td>
        <a href="/part/detail/<?php echo $part->getId(); ?>">
          <?php echo $part->getName(); ?>
        </a>
      </td>
      <td>
        <?php if($part->getCar()): ?>
        <a href="/car/detail/<?php echo $part->getCar()->getId(); ?>">
          <?php echo $part->getCar()->getName(); ?>
        </a>
        <?php endif; ?>
      </td>
      <td>
        <form action="/part/deletePart" method="POST">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="part_id" value="<?php echo $part->getId(); ?>"/>
          <input class="btn btn-danger" type="submit" value="Delete" />
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>