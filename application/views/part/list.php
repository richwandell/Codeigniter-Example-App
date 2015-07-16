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
    <% parts.forEach(function(part){ %>
    <tr>
      <td>
        <a href="/part/detail/<%- part.id %>">
          <%- part.name %>
        </a>
      </td>
      <td>
        <% if(part.car){ %>
        <a href="/car/detail/<%- part.car.id %>">
          <%- part.car.name %>
        </a>
        <% } %>
      </td>
      <td>
        <form action="/part/deletePart" method="POST">
          <input type="hidden" name="_csrf" value="<%= _csrf %>" />
          <input type="hidden" name="part_id" value="<%- part.id %>"/>
          <input class="btn btn-danger" type="submit" value="Delete" />
        </form>
      </td>
    </tr>
    <% }); %>
    </tbody>
  </table>
</div>