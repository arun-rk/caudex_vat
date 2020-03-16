<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function() {

      
        function image(n) {
            if (n !== null) {
                return '<div style="width:32px; margin: 0 auto;"><a href="<?=base_url();?>uploads/'+n+'" class="open-image"><img src="<?=base_url();?>uploads/thumbs/'+n+'" alt="" class="img-responsive"></a></div>';
            }
            return '';
        }

        var table = $('#catData').DataTable({
          'ajax' : { url: '<?=site_url('uom/get_gst_groups');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': false, exportOptions: { columns: [ 0, 1,2] } },
            { extend: 'excelHtml5', 'footer': false, exportOptions: { columns: [ 0, 1,2 ] } },
            { extend: 'csvHtml5', 'footer': false, exportOptions: { columns: [ 0, 1,2] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': false,
            exportOptions: { columns: [ 0, 1,2 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id" },
            { "data": "base_name" },
            // { "data": "sgst" },
            // { "data": "cgst" },
            { "data": "symbol" },
            { "data": "Actions", "searchable": false, "orderable": false }
            ]

        });

        $('#search_table').on( 'keyup change', function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                table.search( this.value ).draw();
            }
        });

        $('#catData').on('click', '#edit-data', function() 
        {
        var data = table.row($(this).parents('tr')).data();
            
            $("#base_name").val(data['base_name']);
            $("#symbol").val(data['symbol']);
            $("#hidid").val(data['id']);
            $("#crude").html('<button class="btn btn-info" type="button" onclick="update()">Update</button>')
            console.log(data);
         });

        $('#catData').on('click', '#delete-data', function() 
        {
        var data = table.row($(this).parents('tr')).data();
           
            var id=data['id'];
            if(confirm("Are you sue to delete this data"))
            {
                
		var ajaxval = {
				id : id
		}
                    $.ajax({
                        type: "GET",
                        url: '<?php echo site_url() ?>uom/deleteUom',
                        data: ajaxval ,
                        success: function(result){
                        //alert(result);
                        if(result==1)
                        {
                        alert("Deleted Successfully");
                        location.reload();
                        }
                        else {
                        alert("Failed to delete");
                        }
                        }
                    }); 
            }
            
         });


    });

</script>
<script>
    $(document).ready(function() {
        $('#catData').on('click', '.image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
        $('#catData').on('click', '.open-image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').find('.image').attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src',a_href);
            $('#picModal').modal();
            return false;
        });
    });
</script>

<section class="content">
    <form name="frm" id="frm" action="" method="post">
    <div class="row">
        <div class="col-md-4">
        <div class="box box-primary">
                <div class="box-header">
                    Add New
                </div>
                <div class="box-body">
                 <div id="form" class="panel panel-warning">
                 <div class="panel-body">
                     <div class="form-group">
                     <div class="row">
                         <div class="col-md-12">
                         <label>Base Name</label>
                            <input type="text" class="form-control" name="base_name" id="base_name">
                         </div>
                      </div>
                     </div>
                    <input type="hidden" class="form-control" name="hidid" id="hidid">
                     <div class="form-group">
                      <div class="row">
                         <div class="col-md-12">
                         <label>Symbol</label>
                            <input type="text" class="form-control" name="symbol" id="symbol">
                         </div>
                      </div>
                    </div>
                        <div id="crude">
                        <button type="button" class="btn btn-primary" onclick="saveData()"><?= lang("submit"); ?></button>
                        </div>
                   

                 </div>

                 </div>

                   
                </div>
             </div>
        </div>
        <div class="col-xs-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>Saved Data </h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="catData" class="table table-striped table-bordered table-condensed table-hover" style="margin-bottom:5px;">
                            <thead>
                                <tr class="active">
                                    <th style="width: 200px;"><?= lang("id"); ?></th>
                                    <th style="width: 200px;"><?= lang("Base name"); ?></th>
                                    <th style="width: 200px;"><?= lang('Symbol'); ?></th>
                                    <th style="width:75px;"><?= lang('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center;">
                                    <td colspan="4" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
                            </tbody>
                         </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    function saveData()
    {
       var basename=$("#base_name").val();
       if(basename!="")
       {
          
        $.ajax({
        type: "GET",
        url: '<?php echo site_url() ?>uom/saveUom',
        data: $("form").serialize() ,
        success: function(result){
        //alert(result);
            if(result==1)
            {
                alert("Saved Successfully");
                location.reload();
            }
            else {
                alert("Failed to save");
            }

        }
    });
       }
       else
       {
        alert("Please enter base name");
       }
    }
    function update()
    {
        var basename=$("#base_name").val();
       if(basename!="")
       {
        $.ajax({
        type: "GET",
        url: '<?php echo site_url() ?>uom/updateUom',
        data: $("form").serialize() ,
        success: function(result){
        //alert(result);
            if(result==1)
            {
                alert("Updated Successfully");
                location.reload();
            }
            else {
                alert("Failed to update");
            }

        }
    });
       }

    }
    //

   
</script>