function Page(opt){
		var set = $.extend({num:null,startnum:1,elem:null,callback:null},opt||{});
		if(set.startnum>set.num||set.startnum<1){
			set.startnum = 1;
		}
		var n = 0,htm = '';
		var clickpages = {
			elem:set.elem,
			num:set.num,
			callback:set.callback,
			init:function(){
				this.elem.next('div.pageJump').children('.button').unbind('click')
				this.JumpPages();
				this.elem.children('li').click(function () {
					var txt = $(this).children('a').text();
					var page = '', ele = null;
					var page1 = parseInt(clickpages.elem.children('li.active').attr('page'));
					if (isNaN(parseInt(txt))) {
						switch (txt) {
							case '下一页':
								if (page1 == clickpages.num) {
									return;
								}
								if (page1 >= (clickpages.num - 2) || clickpages.num <= 5 || page1 < 3) {
									ele = clickpages.elem.children('li.active').next();
								} else {
									clickpages.newPages('next', page1 + 1);
									ele = clickpages.elem.children('li.active');
								}
								break;
							case '上一页':
								if (page1 == '1') {
									return;
								}
								if (page1 >= (clickpages.num - 1) || page1 <= 3 || clickpages.num <= 5) {
									ele = clickpages.elem.children('li.active').prev();
								} else {
									clickpages.newPages('prev', page1 - 1);
									ele = clickpages.elem.children('li.active');
								}
								break;
							case '«':
								if (page1 == '1') {
									return;
								}
								if (clickpages.num > 5) {
									clickpages.newPages('«', 3);
								}
								ele = clickpages.elem.children('li[page=1]');
								break;
							case '»':
								if (page1 == clickpages.num) {
									return;
								}
								if (clickpages.num > 5) {
									clickpages.newPages('»', clickpages.num - 3);
								}
								ele = clickpages.elem.children('li[page=' + clickpages.num + ']');
								break;
							case '...':
								return;
						}
					} else {
						if ((parseInt(txt) >= (clickpages.num - 3) || parseInt(txt) <= 3) && clickpages.num > 5) {
							clickpages.newPages('jump', parseInt(txt));
						}
						ele = $(this);
					}
					page = clickpages.actPages(ele);
					if (page != '' && page != page1) {
						if (clickpages.callback){
							clickpages.callback(parseInt(page));
						}
					}
				});
			},
			//active
			actPages:function (ele) {
				ele.addClass('active').siblings().removeClass('active');
				return clickpages.elem.children('li.active').text();
			},
			JumpPages:function () {
				this.elem.next('div.pageJump').children('.button').click(function(){
					var i = parseInt($(this).siblings('input').val());
					if(isNaN(i)||(i<=0)||i>clickpages.num){
						return;
					}else if(clickpages.num>5){
						clickpages.newPages('jump',i);
					}else{
						var ele = clickpages.elem.children('li[page='+i+']');
						clickpages.actPages(ele);
						if (clickpages.callback){
							clickpages.callback(i);
						}
						return;
					}
					if (clickpages.callback){
						clickpages.callback(i);
					}
				})
			},

			//newpages
			newPages:function (type, i) {
				var html = "";
				switch (type) {
					case "next":
						if (i > (this.num - 3)) {
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + (i - 1) + '"><a href="#">' + (i - 1) + '</a></li>\
											<li class="active" page="' + i + '"><a href="#">' + i + '</a></li>\
											<li page="' + (i + 1) + '"><a href="#">' + (i + 1) + '</a></li>\
											<li page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						} else {
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + (i - 1) + '"><a href="#">' + (i - 1) + '</a></li>\
											<li class="active" page="' + i + '"><a href="#">' + i + '</a></li>\
											<li page="' + (i + 1) + '"><a href="#">' + (i + 1) + '</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						}
						break;
					case "prev":
						if (i < 4) {
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li page="2"><a href="#">2</a></li>\
											<li class="active" page="3"><a href="#">3</a></li>\
											<li page="4"><a href="#">4</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						} else {
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + (i - 1) + '"><a href="#">' + (i - 1) + '</a></li>\
											<li class="active" page="' + i + '"><a href="#">' + i + '</a></li>\
											<li page="' + (i + 1) + '"><a href="#">' + (i + 1) + '</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						}
						break;
					case "«" :
						html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li class="active" page="1"><a href="#">1</a></li>\
											<li page="2"><a href="#">2</a></li>\
											<li page="3"><a href="#">3</a></li>\
											<li page="4"><a href="#">4</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						break;
					case "»" :
						html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + (this.num - 3) + '"><a href="#">' + (this.num - 3) + '</a></li>\
											<li page="' + (this.num - 2) + '"><a href="#">' + (this.num - 2) + '</a></li>\
											<li page="' + (this.num - 1) + '"><a href="#">' + (this.num - 1) + '</a></li>\
											<li class="active" page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						break;
					case "jump" :
						var htm = '';
						if (i >= (this.num - 3)) {
							for (var n = 3; n >= 0; n--) {
								var ht = '<li page="' + (this.num - n) + '"><a href="#">' + (this.num - n) + '</a></li>';
								if (i == this.num - n) {
									ht = '<li class="active" page="' + (this.num - n) + '"><a href="#">' + (this.num - n) + '</a></li>'
								}
								htm += ht;
							}
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											' + htm + '\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
							i = 5;
						} else if (i <= 4) {
							for (var n = 1; n <= 4; n++) {
								var ht = '<li page="' + n + '"><a href="#">' + n + '</a></li>';
								if (i == n) {
									ht = '<li class="active" page="' + n + '"><a href="#">' + n + '</a></li>'
								}
								htm += ht;
							}
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											' + htm + '\
											<li><a href="#">...</a></li>\
											<li  page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
							i = 5;
						} else {
							html = '<li><a href="#" aria-label="Previous">&laquo;</a></li>\
											<li><a href="#">上一页</a></li>\
											<li page="1"><a href="#">1</a></li>\
											<li><a href="#">...</a></li>\
											<li page="' + (i - 1) + '"><a href="#">' + (i - 1) + '</a></li>\
											<li class="active" page="' + i + '"><a href="#">' + i + '</a></li>\
											<li page="' + (i + 1) + '"><a href="#">' + (i + 1) + '</a></li>\
											<li><a href="#">...</a></li>\
											<li  page="' + this.num + '"><a href="#">' + this.num + '</a></li>\
											<li><a href="#">下一页</a></li>\
											<li><a href="#" aria-label="Next">&raquo;</a></li>';
						}
				}

				if (this.num > 5 || this.num < 3) {
					set.elem.html(html);
					clickpages.init({num:set.num,elem:set.elem,callback:set.callback});
				}
			}
		}
		if(set.num<=1){
			$(".pagination").html('');
			return;
		}else if(parseInt(set.num)<=5){
			n = parseInt(set.num);
			var html='<li><a href="#" aria-label="Previous">&laquo;</a></li>\
					<li><a href="#">上一页</a></li>';
			for(var i=1;i<=n;i++){
				if(i==set.startnum){
					html+='<li class="active" page="'+i+'"><a href="#">'+i+'</a></li>';
				}else{
					html+='<li page="'+i+'"><a href="#">'+i+'</a></li>';
				}
			}
			html +='<li><a href="#">下一页</a></li>\
					<li><a href="#" aria-label="Next">&raquo;</a></li>';
			set.elem.html(html);
			clickpages.init();
		}else{
			clickpages.newPages("jump",set.startnum)
		}
}
