var cgst=0;
var cgst_Tax=0;
var sgst=0;
var sgst_Tax=0;

function loadItems() {

    get("spoitems") && (total = 0, $("#poTable tbody").empty(), spoitems = JSON.parse(get("spoitems")), $.each(spoitems, function() {
        var t = this,
            e = 1 == Settings.item_addition ? t.item_id : t.id;
        spoitems[e] = t;
        var o = t.row.id,
		 ex = t.row.expiry,
            s = t.row.cost,
            a = t.row.qty,
            i = t.row.code,
			cgst=t.gst.cgst,
			sgst=t.gst.sgst,
			
			
            n = t.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"),
            d = (new Date).getTime(),
            m = $('<tr id="' + d + '" class="' + e + '" data-item-id="' + e + '"></tr>');
			
			 sgst_Tax=  ((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.sgst))/100  ;
			 cgst_Tax=  ((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.cgst))/100  ;
			
        tr_html = '<td style="min-width:100px;"><input name="product_id[]" type="hidden" class="rid" value="' + o + '"><span class="sname" id="name_' + d + '">' + n + " (" + i + ")</span></td>", 
	 
	
	tr_html += '<td style="padding:2px;"><input class="form-control input-sm kb-pad text-center rquantity" name="quantity[]" type="text" value="' + a + '" data-id="' + d + '" data-item="' + e + '" id="quantity_' + d + '" onClick="this.select();"></td>', 
		tr_html += '<td style="padding:2px; min-width:80px;"><input class="form-control input-sm kb-pad text-center rcost" name="cost[]" type="text" value="' + s + '" data-id="' + d + '" data-item="' + e + '" id="cost_' + d + '" onClick="this.select();"></td>', 
	
	tr_html += '<td class="text-right d_none"><input class="form-control input-sm kb-pad text-center cgst" name="cgst[]" type="text" value="' + cgst + '" data-id="' + cgst + '" data-item="' + cgst + '" id="quantity_' + cgst + '" onClick="this.select();"></td>',
	tr_html += '<td class="text-right d_none"><input class="form-control input-sm kb-pad text-center cgst_Tax" name="cgst_Tax[]" type="text" value="' + cgst_Tax + '" data-id="' + cgst_Tax + '" data-item="' + cgst_Tax + '" id="quantity_' + cgst_Tax + '" onClick="this.select();"></td>',  
	
	tr_html += '<td class="text-right d_none"><input class="form-control input-sm kb-pad text-center sgst" name="sgst[]" type="text" value="' + sgst + '" data-id="' + sgst + '" data-item="' + sgst + '" id="quantity_' + sgst + '" onClick="this.select();"></td>',
	tr_html += '<td class="text-right d_none"><input class="form-control input-sm kb-pad text-center sgst_Tax" name="sgst_Tax[]" type="text" value="' + sgst_Tax + '" data-id="' + sgst_Tax + '" data-item="' + sgst_Tax + '" id="quantity_' + sgst_Tax + '" onClick="this.select();"></td>',   
	
	
	tr_html += '<td class="text-right"><input class="form-control input-sm kb-pad text-center vat"  type="text" value="' + (Number(sgst)+Number(cgst)) + '" data-id="' + (Number(sgst)+Number(cgst))  + '" data-item="' + (Number(sgst)+Number(cgst))  + '" id="quantity_' + (Number(sgst)+Number(cgst))  + '" onClick="this.select();" readonly ></td>',
	tr_html += '<td class="text-right"><input class="form-control input-sm kb-pad text-center vat_Tax"  type="text" value="' + (Number(sgst_Tax)+Number(cgst_Tax)) + '" data-id="' + (Number(sgst_Tax)+Number(cgst_Tax)) + '" data-item="' + (Number(sgst_Tax)+Number(cgst_Tax)) + '" id="quantity_' + (Number(sgst_Tax)+Number(cgst_Tax)) + '" onClick="this.select();" readonly></td>',   
	
	

	tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + d + '">' +formatMoney((parseFloat(s)  * parseFloat(a)) + (((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.cgst))/100)+(((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.sgst))/100))+ "</span></td>", tr_html += '<td class="text-center"><i class="fa fa-trash-o tip pointer spodel" id="' + d + '" title="Remove"></i></td>', 
		m.html(tr_html), m.prependTo("#poTable"), total += (parseFloat(s)  * parseFloat(a)) + (((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.cgst))/100)+(((parseFloat(s)* parseFloat(a))* parseFloat(t.gst.sgst))/100)
    }), grand_total = formatMoney(total), $("#gtotal").text(grand_total), $("#add_item").focus())
	
	$(".expiry").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
}
   
function add_order_item(t) {

    var e = 1 == Settings.item_addition ? t.item_id : t.id;
    return spoitems[e] ? spoitems[e].row.qty = parseFloat(spoitems[e].row.qty) + 1 : spoitems[e] = t, 
store("spoitems", JSON.stringify(spoitems)), loadItems(), !0
}
$(document).ready(function() {
    loadItems(), $("#date").inputmask("yyyy-mm-dd hh:mm", {
        placeholder: "yyyy-mm-dd hh:mm"
    }), 
	$("#add_item").autocomplete({
        source: base_url + "purchases/suggestions",
        minLength: 1,
        autoFocus: !1,
        delay: 200,
        response: function(t, e) {
            $(this).val().length >= 16 && 0 == e.content[0].id ? (bootbox.alert(lang.no_match_found, function() {
                $("#add_item").focus()
            }), $(this).val("")) : 1 == e.content.length && 0 != e.content[0].id ? (e.item = e.content[0], $(this).data("ui-autocomplete")._trigger("select", "autocompleteselect", e), $(this).autocomplete("close")) : 1 == e.content.length && 0 == e.content[0].id && (bootbox.alert(lang.no_match_found, function() {
                $("#add_item").focus()
            }), $(this).val(""))
        },
        select: function(t, e) {
			 
            t.preventDefault(), 0 !== e.item.id ? add_order_item(e.item) && $(this).val("") : bootbox.alert(lang.no_match_found)
        }
    }),
	 $("#add_item_barcode").autocomplete({
        source: base_url+'pos/suggestions',
        minLength: 1,
        autoFocus: false,
        delay: 200,
        response: function (event, ui) {
			 
            if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                bootbox.alert(lang.no_match_found, function () {
                    $('#add_item_barcode').focus();
                });
                $(this).val('');
            }
            else if (ui.content.length == 1 && ui.content[0].id != 0) {
                ui.item = ui.content[0];
               // $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                // $(this).autocomplete('close');
            }
            else if (ui.content.length == 1 && ui.content[0].id == 0) {
                bootbox.alert(lang.no_match_found, function () {
                    $('#add_item_barcode').focus();
                });
                $(this).val('');
            }
        },
        select: function (event, ui) {
            event.preventDefault();
            if (ui.item.id !== 0) {
                var row = add_invoice_item(ui.item);
                if (row)
                    $(this).val('');
            } else {
                bootbox.alert(lang.no_match_found);
            }
        }
    }),
	  $("#add_item").bind("keypress", function(t) {
        13 == t.keyCode && (t.preventDefault(), $(this).autocomplete("search"))
    }), $("#add_item").focus(), $("#reset").click(function(t) {
        bootbox.confirm(lang.r_u_sure, function(t) {
            t && (get("spoitems") && remove("spoitems"), window.location.reload())
        })
    }), $(document).on("change", ".rquantity", function() {
        var t = $(this).closest("tr"),
            e = parseFloat($(this).val()),
            o = t.attr("data-item-id");
        spoitems[o].row.qty = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
    }),$(document).on("change", ".cgst", function() {
        var t = $(this).closest("tr"),
            e = parseFloat($(this).val()),
            o = t.attr("data-item-id");
        spoitems[o].gst.cgst = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
    }),$(document).on("change", ".cgst_Tax", function() {
        var t = $(this).closest("tr"),
            e = parseFloat($(this).val()),
            o = t.attr("data-item-id");
        spoitems[o].row.qty = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
    }),$(document).on("change", ".sgst", function() {
				var t = $(this).closest("tr"),
				e = parseFloat($(this).val()),
				o = t.attr("data-item-id");
				spoitems[o].gst.sgst = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
		}),$(document).on("change", ".vat", function() {
			var t = $(this).closest("tr"),
			e = parseFloat($(this).val());
			o = t.attr("data-item-id");
			spoitems[o].gst.cgst = e/2, store("spoitems", JSON.stringify(spoitems)), loadItems(),
			spoitems[o].gst.sgst = e/2, store("spoitems", JSON.stringify(spoitems)), loadItems(),
			$(this).parents('tr').children()[3] = e/2;
			$(this).parents('tr').children()[5] = e/2;
	}),$(document).on("change", ".vat_Tax", function() {
		var t = $(this).closest("tr"),
		e = parseFloat($(this).val()),
		o = t.attr("data-item-id");
		spoitems[o].row.qty =e/2;
		store("spoitems", JSON.stringify(spoitems)); loadItems();
}),$(document).on("change", ".sgst_Tax", function() {
        var t = $(this).closest("tr"),
            e = parseFloat($(this).val()),
            o = t.attr("data-item-id");
        spoitems[o].row.qty = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
    }), $(document).on("change", ".rcost", function() {
        var t = $(this).closest("tr"),
            e = parseFloat($(this).val()),
            o = t.attr("data-item-id");
        spoitems[o].row.cost = e, store("spoitems", JSON.stringify(spoitems)), loadItems()
    })
});
//# sourceMappingURL=maps/purchases.min.js.map