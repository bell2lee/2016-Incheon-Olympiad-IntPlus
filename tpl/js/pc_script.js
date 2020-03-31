
var img_up = new Array();
img_up_index = 0;

function write_layer_show(){
	$(".write_layer").show(0).animate({top:"0%", opacity:"1"}, 300);
	$(".write_bottom").fadeIn(300);
	$("body").css({overflow:"hidden"});
}

function write_layer_hide(){
	$(".write_layer").animate({top:"100%", opacity:".4"}, 300).hide(0);
	$(".write_bottom").fadeOut(300);
	$("body").css({overflow:"auto"});
}

//$(".header_ajaxLoading").fadeIn(300); 상단 로딩바 출력
function timeline_document_load(lastDocNum, group_num){
	$.get("/?mid=group_timeline_query&p-type=one", { "group_num": group_num, "type":"new_doc", "lastDocNum": lastDocNum},  function(obj){
		switch(obj.state){
				case "error1":
					alert("세션이 만료되었습니다. 새로고침 후 다시 로그인 해주세요");
					break;
				case "error2":
					alert("필수 파라미터 검증에 실패 했습니다.");
					break;
				case "error3":
					alert("없는 그룹이거나 그룹 삭제된 그룹입니다.");
					break;
				case "error4":
					//alert("권한이 없습니다.");
					break;
				case "success":
					//alert(obj.document_num[0]);
					//var this_mine = '<div class="right"><i class="fonti um-ellipsis-h" style="font-size:30px;"></i></div>';
					if(lastDocNum >= 1){
						i = 0;
						while(obj.writer_num[i]){
							
							this_doc = "<div class='timeline_box' data-docnum='" + obj.document_num[i] + "'><div class='writer'><img src='./tpl/img/non_profile.png' class='circle responsive-img'><div class='wirter_name'><a href='/?mid=member_view&member_num=" + obj.writer_num[i] + "'>" + obj.writer[i] + "</a></div></div><div class='doc'>" + obj.document[i] + "</div>";
							
							// 이미지가져오기
							j = 0;
							
							
							if(obj.img[i]){
								this_doc += "<div class='img_zone'><i class='fonti um-chevron-right um-2x left_a'></i><ul>";
								while(obj.img[i][j]){
									this_doc += "<li><img height='300' class='materialboxed' src='/php/upload_img/" + obj.img[i][j] + "'></li>";
									j++;
								}
								this_doc += "</ul></div>";
							}

							this_doc += "</div>";
							$(".timeline_zone").append(this_doc);
							i++;
						}
					}else{
						i = obj.writer_num.length - 1;
						while(obj.writer_num[i]){

							this_doc = "<div class='timeline_box' data-docnum='" + obj.document_num[i] + "'><div class='writer'><img src='./tpl/img/non_profile.png' class='circle responsive-img'><div class='wirter_name'><a href='/?mid=member_view&member_num=" + obj.writer_num[i] + "'>" + obj.writer[i] + "</a></div></div><div class='doc'>" + obj.document[i] + "</div>";
							
							// 이미지가져오기
							j = 0;
							
							
							if(obj.img[i]){
								this_doc += "<div class='img_zone'><i class='fonti um-chevron-right um-2x left_a'></i><ul>";
								while(obj.img[i][j]){
									this_doc += "<li><img height='300' class='materialboxed' src='/php/upload_img/" + obj.img[i][j] + "'></li>";
									j++;
								}
								this_doc += "</ul></div>";
							}

							this_doc += "</div>";

							$(".timeline_zone").prepend(this_doc);
	
							i--;
						}
					}
					break;
			}
			$('.materialboxed').materialbox();
	}, "json"); 
}

function timeline_back_load(){
	var Indexs = $('.timeline_zone .timeline_box').index('.timeline_zone');
	
	lastDocumentNumber = $(".timeline_box:eq(" + Indexs + ")").attr("data-docnum");
	
	timeline_document_load(lastDocumentNumber, gp_num);
}


$(function(){
    $(document).on('click', 'input[type=file]', function () {
        Android.selectImage();   
    });
});

function updateImage(mime_type, encode_image){
	//alert(mime_type);
	//alert();
	$(".preloader-wrapper").fadeIn(300);
	$.post("/php/upload.php", {m:'1', data: mime_type, basea: encode_image}, function(data){
		//alert();
		//a = data;
		img_up[img_up_index] = new Array(data);
		
		img_up_index++;
		$(".editer .row").append("<div class='file_bo'>" + data + "</div>");
		
		$(".preloader-wrapper").fadeOut(300);

	});

	/*$.ajax({
		type: "POST",
		url: "/php/upload.php?m=1",
		data: { myfile:imgData},
		dataType: 'json',
		contentType: "POST",
		success: function (result) {
			alert(data);
			img_up[img_up_index] = data;

			img_up_index++;
		}

	});*/

	//f_obj.startUpload();
}


function newdoc_write(){
	//http://int.kr3.kr/?mid=group_timeline_query&group_num=2&type=write_doc&p-type=on
	if($(".editer textarea").val()){
		//alert(img_up[0]);
		var img_gogo = JSON.stringify(img_up);
		//alert(img_gogo);return false;
		$.post("/?mid=group_timeline_query&group_num=" + gp_num + "&type=write_doc&p-type=one", { doc_text: $(".editer textarea").val(),'imgs[]':img_gogo }, function(obj){
			//console.log(obj);
			switch(obj.state){
				case "error1":
					alert("세션이 만료되었습니다. 새로고침 후 다시 로그인 해주세요");
					break;
				case "error2":
					alert("필수 파라미터 검증에 실패 했습니다.");
					break;
				case "error3":
					alert("없는 그룹이거나 그룹 삭제된 그룹입니다.");
					break;
				case "error4":
					alert("권한이 없습니다.");
					break;
				case "success":
					location='/?mid=group_timeline&group_num=' + gp_num;
					
					break;
			}

		}, "json");

	/*$.post( "test.php", { func: "getNameAndTime" }, function( data ) {
	console.log( data.name ); // John
	console.log( data.time ); // 2pm
	}, "json");*/
	}else{ alert("내용을 입력하세요"); }
}

function getRequest() {
    if(location.search.length > 1) {
        var get = new Object();
        var ret = location.search.substr(1).split('&');
        for(var i = 0; i < ret.length; i++) {
            var r = ret[i].split('=');
            get[r[0]] = r[1];
        }
        return get;
    }else{
        return false;
    }
}


$(".button-collapse").sideNav();

(function($){
  $(function(){
	  
	  var f_obj = $("#fileuploader").uploadFile({
		url:"/php/upload.php",
		fileName:"myfile",
		returnType:"json",
		acceptFiles:"image/",
		onSuccess:function(files,data,xhr,pd)
		{
			//files: list of files
			//data: response from server
			//xhr : jquer xhr object
			img_up[img_up_index] = data;
			img_up_index++;
		}
	});
	var get = getRequest();
    $('.button-collapse').sideNav({
			menuWidth: 300, // Default is 240
			edge: 'left', // Choose the horizontal origin
			closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
		}
	);

	$('select').material_select();
	
	$(window).scroll(function(){
		if($(window).scrollTop() == $(document).height() - $(window).height()){
			if(get['mid'] == "group_timeline"){
				timeline_back_load();
			}else if(get['mid'] == "group_search"){
				//$(".group_search_result")
				// 더 불러오기
			}
	        
			
	    }
	});


	$('#textarea1').trigger('autoresize');
	$('.materialboxed').materialbox();
	$('.datepicker').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15 // Creates a dropdown of 15 years to control year
	});

  });
})(jQuery);
