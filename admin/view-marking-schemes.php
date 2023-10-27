<!DOCTYPE html>
<html>
  <?php 
    include "../includes/header.php";
    include "../includes/admin-navbar.php";
    include "../db_handler.php";
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"/>
    <title></title>
</head>
<body>
<div class="container">
    <div class="row">
        <form class="form-horizontal" style="float: right;" action="view-marking-schemes.php" method="post" name="export" enctype="multipart/form-data">
          <div class="form-group">
            <div class="col-md-4 col-md-offset-4">
              <input type="submit" name="export" class="btn btn-success" value="Export As CSV File"/>
            </div>
          </div>                    
        </form> 
    	<h1>List Of Marking Schemes</h1>
    	<hr>
        <div class="panel panel-primary filterable" style="border-color: #00bdaa;">
            <div class="panel-heading" style="background-color: #00bdaa;">
                <h3 class="panel-title">Marking Schemes</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter Search</button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Module Code" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Module Name" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Assessment Code" disabled></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $output = '';
                        if(isset($_POST["query"]))
                        {
                         $search = mysqli_real_escape_string($conn, $_POST["query"]);
                         $query = "
                          SELECT DISTINCT * FROM marking_scheme 
                          WHERE module_code LIKE '%".$search."%'
                          OR module_name LIKE '%".$search."%' 
                          OR assessment_code LIKE '%".$search."%' 
                         ";
                        }
                        else
                        {
                         $query = "
                          SELECT DISTINCT module_code, module_name, assessment_code FROM marking_scheme
                         ";
                        }
                        $result = mysqli_query($conn, $query);
                        if(mysqli_num_rows($result) > 0)
                        {

                         while($row = mysqli_fetch_array($result))
                         {
                        $module = $row["module_code"];
                            
                          $output .= '
                           <tr>
                            <td>'.$row["module_code"].'</td>
                            <td>'.$row["module_name"].'</td>
                            <td>'.$row["assessment_code"].'</td>
                           </tr>
                          ';
                         }
                         echo $output;
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<style type="text/css">
    .filterable {
    margin-top: 15px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('.filterable .btn-filter').click(function(){
            var $panel = $(this).parents('.filterable'),
            $filters = $panel.find('.filters input'),
            $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function(e){
            var code = e.keyCode || e.which;
            if (code == '9') return;

            var $input = $(this),
            inputContent = $input.val().toLowerCase(),
            $panel = $input.parents('.filterable'),
            column = $panel.find('.filters th').index($input.parents('th')),
            $table = $panel.find('.table'),
            $rows = $table.find('tbody tr');

            var $filteredRows = $rows.filter(function(){
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });

            $table.find('tbody .no-result').remove();
            
            $rows.show();
            $filteredRows.hide();
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
            }
        });
    });
</script>

<?php 
   if(isset($_POST["export"])){
      $result = "SELECT * FROM marking_scheme";
      $row = mysqli_query($conn, $result) or die(mysqli_error($conn));

      $fp = fopen('../spreadsheets/marking-schemes.csv', 'w');

      while($val = mysqli_fetch_array($row, MYSQLI_ASSOC)){
          fputcsv($fp, $val);
      }
      fclose($fp); 
    }  
?>