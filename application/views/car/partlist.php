<?php $this->load->view('header', array("page" => "/car/carlist")); ?>
<ol class="breadcrumb">
  <li>
    <a href="/car/carlist">Cars</a>
  </li>
  <li>
    <a href="/car/detail/<?php echo $car->getId(); ?>">
      <?php echo $car->getName(); ?>
    </a>
  </li>
  <li class="active">Parts</li>
</ol>
<div class="panel panel-default" id="passenger_list">
  <table class="table table-striped table-hover">
    <caption>Part List
      <div id="part_list_total">Total: $ <%- parts_total %></div>
    </caption>
    <thead>
    <tr>
      <th>Name</th>
      <th>Price ($)</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($parts as $part): ?>
      <tr>
        <td><?php echo $part->getName(); ?></td>
        <td>$ <?php echo $part->formattedPrice(); ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="panel panel-default" id="new_passenger">
  <form class="form-horizontal" action="/part/addPart" method="POST">
    <input type="hidden"
           name="<?php echo $this->security->get_csrf_token_name(); ?>"
           value="<?php echo $this->security->get_csrf_hash(); ?>">

    <div class="form-group" id="part_name_error">
      <label for="part_name" class="col-sm-2 control-label">Part Name</label>

      <div class="col-sm-10">
        <input value="" type="text"
               class="form-control" id="part_name_value"
               placeholder="Part Name" name="part_name">
      </div>
    </div>
    <div class="form-group" id="part_price_error">
      <label for="part_price" class="col-sm-2 control-label">Price ($)</label>

      <div class="col-sm-10">
        <input value="" type="number" min="0"
        max="9999" step="0.01" size="4" class="form-control" id="part_price_value"
        name="part_price">
      </div>
    </div>
    <input type="hidden" name="part_car" value="<?php echo $car->getId(); ?>"/>
    <input type="submit" class="btn btn-default" value="Add Part"/>
  </form>
</div>