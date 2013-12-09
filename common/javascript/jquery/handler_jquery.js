DEBUG = true;
$(document).ready(
	function()
	{
		$("div.toggle_menu a.toggle_menu").click(function(){
			div_toggle = $(this).parent().parent().next("div.tohide");
			if (div_toggle.css('display') == 'none') {
				div_toggle.slideDown('slow');
				$(this).find("img").each(function(i) {
					//alert(newimage.substr(0,newimage.length-8) + 'minus.gif');
					$(this).attr('src', IMAGE_PATH + 'minus.gif');
				});				
			} else {
				
				div_toggle.slideUp('slow');
				$(this).find("img").each(function(i) {
					$(this).attr('src', IMAGE_PATH + 'plus.gif');
				});
			}
			
		});
		
		$("div.toggle_hide2show a.toggle_menu").toggle(function(){
			//div_toggle = $(this).parent().parent().parent().find("div.toggle_hide2show");
			//div_toggle.css("background-color","#555555");
			//$(this).parent().parent().append("&nbsp;");
			$(this).find("img").each(function(i) {
				newimage = $(this).attr('src');
				$(this).attr('src', newimage.substr(0,newimage.length-4) + '_on.png');
			});
			div_toggle = $(this).parent().parent().find("div.tohide");
			//alert(div_toggle.html());
			div_toggle.animate({ height: 'show', opacity: 'show' }, 'slow');
			
		},function(){
			//div_toggle = $(this).parent().parent().parent().find("div.toggle_hide2show");
			//div_toggle.css("background-color","#FFFF44");
			//$(this).parent().parent().append("&nbsp;");
			$(this).find("img").each(function(i) {
				newimage = $(this).attr('src');
				$(this).attr('src', newimage.substr(0,newimage.length-7) + '.png');
			});
			div_toggle = $(this).parent().parent().find("div.tohide");
			div_toggle.animate({ height: 'hide', opacity: 'hide' }, 'slow');
		});
		
		$("div.toggle_show2hide a.toggle_menu").toggle(function(){
			$(this).find("img").each(function(i) {
				newimage = $(this).attr('src');
				$(this).attr('src', newimage.substr(0,newimage.length-7) + '.png');
			});
			div_toggle = $(this).parent().find("div.tohide");
			//alert(div_toggle.html());
			div_toggle.animate({ height: 'hide', opacity: 'hide' }, 'slow');
			
		},function(){			
			$(this).find("img").each(function(i) {
				newimage = $(this).attr('src');
				$(this).attr('src', newimage.substr(0,newimage.length-4) + '_on.png');
			});
			div_toggle = $(this).parent().find("div.tohide");
			//alert(div_toggle.html());
			div_toggle.animate({ height: 'show', opacity: 'show' }, 'slow');
		});
		
		
		$("div.toggle_show2hide a.hide_help").click(function(){
			div_toggle = $(this).parent().parent().parent().find("div.tohide");
			//alert(div_toggle.html());
			div_toggle.animate({ height: 'hide', opacity: 'hide' }, 'slow');
			
		});
		
		
		/*
		
		$("#toggle_showhide a").toggle(function(){
			$("#tohide").animate({ height: 'hide', opacity: 'hide' }, 'slow');
		},function(){
			$("#tohide").animate({ height: 'show', opacity: 'show' }, 'slow');
		});
		
		$("a").click(function(){
		   $(this).hide("slow");
		   return false;
		});
		 
		$("#treeItem").find("li").each(function(i) {			
			$(this).html( $(this).html() + " BAM! " + i );
		});
		
		$('.editInput').each(function(i){
			setClickable(this, i, 'INPUT');
		});
		
		*/
		
	}
);




/*
function applyChanges(obj, cancel, n) {
	if(!cancel) {
		var t = $(obj).parent().siblings(0).val();
		$.post("test.php",{
		  content: t,
		  n: n
		},function(txt){
			alert( txt);
		});
	} else {
		var t = cancel;
	}
	if(t=='') t='(click to add text)';
	//alert ($(obj).parent().parent().after('<span class="editInput">'+t+'</span>').html());	
	$(obj).parent().parent().after('<span class="textEdit">'+t+'</span>').remove();
	setOption($(".textEdit").get(n), n);
}


function setClickable(obj, i, type) {
	$(obj).click(function() {
		if (type == 'INPUT'){
			var textarea = '<span><span><INPUT OnClick="this.focus();this.select();" type="text" id="koko" value="'+$(this).html()+'">';
			var button	 = '<input type="button" value="SAVE" class="saveButton" /> OR <input type="button" value="CANCEL" class="cancelButton"/></span></span>';
		}else{
			var textarea = '<div><textarea rows="4" cols="60">'+$(this).html()+'</textarea>';
			var button	 = '<div><input type="button" value="SAVE" class="saveButton" /> OR <input type="button" value="CANCEL" class="cancelButton" /></div></div>';
		}
		var revert = $(obj).html();		
		$(obj).after(textarea+button).remove();
		$('.saveButton').click(function(){saveChanges(this, false, i, type);});
		$('.cancelButton').click(function(){saveChanges(this, revert, i, type);});		
	})
	.mouseover(function() {
		$(obj).addClass("editable");		
	})
	.mouseout(function() {
		$(obj).removeClass("editable");
	});
}//end of function setClickable



$.log( 'DEBUG' );

$('ul.properties').debug();
*/
