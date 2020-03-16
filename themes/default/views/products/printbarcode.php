<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
    <script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
#ProductBarcode
{
	width:210mm !important;
	margin-top: 0px !important;
	height:287mm !important;
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
#ProductBarcode{
    page-break-after: avoid;

}
@media print {
    html, body {
        height: 90%;    
    }
}
 #ProductBarcode {
            border: none;
            page-break-after: always;
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
    /* box-shadow: 0 0 1px black; */
}
.subx img {
	width: 100%;
    height: 50px;
		/* margin:10px; */
}

#Barcodes-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
}
@media print {
	.no-print{
		display:none;
	}
}
</style>
<script>

function template(data,print=false) {
	 console.log(data);
	 
	 var qwe='';
	 $.each(data, function (index, value) { 
		onclick = 'onclick="getbarcode('+value.id+')"'
	 if(print){
	 var onclick='';
	 }
		qwe += '<div '+onclick+' class="subx"><span class="b_name trunc">'+value.name+'</span> '+value.barcode+' <span class="b_code trunc">'+value.code+'</span><span class="b_price trunc">Rs.'+value.price+'</span></div>';
	 });
	 return qwe;
	//  return JSON.stringify(data);
 }
$(function(){
	var id = <?=$_GET['id']?>;
	$.ajax({
				type: "GET",
				url: "<?=site_url('products/print_barcodes__byid/?id=')?>"+id,
				dataType: "json",
				success: function (response) {
						console.log(response);
        var html = template(response.items,true);
        $('  #Barcodes-container').html(html);		
						setTimeout(() => {
							$('#loader').hide();
							window.print();
							window.close();
						}, 3000);
				}
			});
});
</script>
<svg id="loader" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
  <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
    s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
    c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
  <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
    C22.32,8.481,24.301,9.057,26.013,10.047z">
    <animateTransform attributeType="xml"
      attributeName="transform"
      type="rotate"
      from="0 20 20"
      to="360 20 20"
      dur="0.5s"
      repeatCount="indefinite"/>
    </path>
  </svg>
</div>

<!-- <button class="no-print" onclick="window.print()" > Print </button> -->
<div class="barcode" id="Barcodes-container">
				</div>
