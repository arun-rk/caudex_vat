<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="<?= $assets ?>dist/css/pagination.css">
<script src="<?= $assets ?>dist/js/pagination.min.js"></script>
<script type="text/javascript">
 var category,supplier,vat_p,table;
 var pagex;
 var pagin_options = {
    locator: 'items',
    totalNumberLocator: function(response) {
        return response.total;
    },
    pageSize: 20,
		className: 'paginationjs-pages',
    showGoInput: true,
    showGoButton: true,
    formatGoInput: ' <%= input %> ',
    ajax: {
        beforeSend: function() {
            $('#data-container').html('<div style=" height: 200px; display: flex;    align-items: center;    justify-content: center;" ><span class="spiner"><i style="font-size:40px" class="fa fa-spinner fa-pulse"></i></span></div>');
        }
    },
    callback: function(data, pagination) {
				console.log(data);
        var html = template(data);
				console.log(html);
        $('#data-container').html(html);
    }
		}
 function applyfilter(apply_filter) {
	 $(apply_filter).attr('disabled',true);
	 $(apply_filter).text('Filtering...');
		var q =  $('#filter_form').serialize();
	// console.log(q);
	 my_pagination.pagination({
		...pagin_options,
    dataSource: "<?=site_url('products/print_barcodes_2')?>?"+q,
    callback: function(data, pagination) {
				console.log(data);
        var html = template(data);
        $('#data-container').html(html);				
				// console.log(html);
				$(apply_filter).attr('disabled',false);
				$(apply_filter).text('Filter Barcodes');
    }
		})

 }


 $(function(){

	my_pagination = $('#demo').pagination({
		...pagin_options,
    dataSource: "<?=site_url('products/print_barcodes_2')?>",
})

 });
 function template(data,print=false) {
	 
	// console.log((data.length));
	 var qwe='';
	 if(data.length){
	 $.each(data, function (index, value) { 
		onclick = 'onclick="getbarcode('+value.id+')"'
	 if(print){
	 var onclick='';
	 }
		qwe += '<div '+onclick+' class="subx"><span class="b_name trunc">'+value.name+'</span> '+value.barcode+' <span class="b_code trunc">'+value.code+'</span><span class="b_price trunc">Rs.'+value.price+'</span></div>';
	 });
	 }
	 else{
	 console.log(qwe);
		 qwe = '<p style=" text-align: center; color: #c3c3c3; font-size: 17px; padding: 30px; ">No data !</p>'
	 }
	 return qwe;
	//  return JSON.stringify(data);
 }
   
	 function getbarcode(id) {
		//  alert(id);
        window.open('<?=site_url('products/print_barcodes__byid?id=')?>'+id, '_blank',"width=800,height=600");
		//  $('#Barcodes').modal('show');
		 
		//  $('#Barcodes #Barcodes-container').html('<div style=" height: 200px; display: flex;    align-items: center;    justify-content: center;" ><span class="spiner"><i style="font-size:40px" class="fa fa-spinner fa-pulse"></i></span></div>');
		// 	$.ajax({
		// 		type: "GET",
		// 		url: "<?=site_url('products/print_barcodes__byid/')?>"+id,
		// 		dataType: "json",
		// 		success: function (response) {
		// 				console.log(response);
    //     var html = template(response.items,true);
    //     $('#Barcodes #Barcodes-container').html(html);		
						
		// 		}
		// 	});
		 }
</script>
<style>
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
/* -webkit-animation: spin 0.4s cubic-bezier(0, 0.52, 0.82, 0.38) infinite; */
.spiner{
	animation: spin 0.4s cubic-bezier(0, 0.52, 0.82, 0.38) infinite;
}

span.b_name {
    font-weight: 700;
    font-size: 11px;
    padding: 0 15px;
    text-align: center;
    display: block;
}
.b_code{
    float: left;
	/* font-weight: 700; */
    font-size: 11px;
    padding: 0 15px;
    text-align: center;
    display: block;
}
.b_price{
    float: right;
	/* font-weight: 700; */
    font-size: 11px;
    padding: 0 15px;
    text-align: center;
    display: block;
}
/* .sub {
    margin: 10px;
    padding: 5px;
    box-shadow: 0 0 1px black;
} */
.trunc{
	white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.subx {
	margin: 10px;
    box-shadow: 0 0 1px black;
}
.subx img {
	width: 100%;
    height: 50px;
		/* margin:10px; */
}
#data-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}
#Barcodes-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
}
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
 					<div class="box box-primary">
					 <div class="box-header">
					 <h3 class="box-title">Please use the table below to navigate or filter the results.</h3>
					 </div>
					 <div class="box-body">
					 <form id="filter_form" method="GET" class="filter row" style=" margin: 15px; ">
									<div class="form-group col-md-3 col-xs-12">
											<label for="group">Category</label>                            
											<select name="category" id="category" data-placeholder="Select Category" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_categories","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-3 col-xs-12">
											<label for="group">Supplier</label>                            
											<select name="supplier" id="supplier" data-placeholder="Select Supplier" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_suppliers","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-3 col-xs-12">
											<label for="group">Search</label>                            
											<input type="text" class="form-control " name="search" id="search">
									</div>
									<div class="form-group  col-md-3 col-xs-12">
                            <button type="button" style=" margin-top: 23px; " id="apply_filter" onclick="applyfilter(this)" value="Filter Products"  class="btn btn-primary" >Filter Barcodes</button>
									</div>
								</form>	
								<div id="data-container"></div>
								<div class="paginationjs-pages"></div>
								<div id="demo"></div>
					 </div>
					 </div>
        </div>
    </div>
</div>
</section>

<style>
.barcode {
    padding-left: 5mm;
    padding-right: 3mm;
    padding-top: 11mm;
}

.barcodex {
    padding-left: 4px !important;
    padding-right: 4px !important;
    padding-top: 1px !important;
}
#Barcodes
{
	/* width:210mm !important;
	margin-top: 0px !important;
	height:287mm !important; */
}
.main {
		width: 100%;
		float: left;
		padding-left: 0mm !important;
		padding-top: 0mm !important;
		padding-right: 0mm !important;
		border: none;
}
.sub h4 {
		font-size: 12px;
		margin: 0;
		position: relative;
		top: -4px;
}
.sub strong {
		font-size: 9px;
		margin: 0;
		width: 100% !important;
		position: relative;
		top: -8px;
		font-weight: 500;
		white-space: nowrap; 
		overflow: hidden;
		text-overflow: clip;
}
.sub img {
       position: relative;
    top: -6px;
    width: 137px;
		height: 35px;
		
}
.price{
    font-size: 10px;
    margin: 0;
    position: relative;
    top: -4px;
    float: right;
	    margin-right: 16px;
}
.code{
    font-size: 10px;
    margin: 0;
    position: relative;
    top: -4px;
    float: left;
	    margin-left: 16px;
}
.sub {
   /* padding: 4px; */
    float: left;
    width: 40.4mm;
    text-align: center;
		
    height: 20mm;
    /* height: 21.4mm; */
		margin: 0px 18px;
    line-height: 0.9;    
		/* padding-top: 4px; */
}
.modal-body {
		padding-left: 0mm !important;
		padding-top: 0mm !important;
		padding-right: 0mm !important;
} 
#Barcodes{
    page-break-after: avoid;

}
@media print {
    html, body {
        height: 90%;    
    }
		#Barcodes-container{
			display:block;
			background:red;
		}
}
 #Barcodes {
            border: none;
            page-break-after: always;
        }
</style>

<!-- Modal -->
<div class="modal fade" id="Barcodes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width:800px" role="document">
    <div class="modal-content">
      <div class="modal-header  no-printx">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<button type="button" class="close mr10" onclick="window.print();"><i class="fa fa-print"></i></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <div class="barcode" id="Barcodes-container">
				</div>
      </div>
		<div class="modal-footer no-printx">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
			<button class="btn btn-primary" href="javascript:void();" onclick="window.print();"><i class="fa fa-print"></i> <?= lang('print'); ?></button>
		</div>
    </div>
  </div>
</div>





