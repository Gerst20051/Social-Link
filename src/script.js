var aC = {
title: "Social Link",
logged: false,
loginFocus: false,
registerFocus: false,
onKeyDown: function(e){
	var keyCode = e.keyCode || e.which;
	if (keyCode == 13) {
		if (aC.loginFocus) $("#b_login").click();
		else if (aC.registerFocus) $("#b_register").click();
	}
},
stringToBoolean: function(string){
        switch(string.toLowerCase()) {
                case "true": case "yes": case "1": return true;
                case "false": case "no": case "0": case null: return false;
                default: return Boolean(string);
        }
},
init: function(){
	$.get('ajax.php', {p:"logged"}, function(response) {
		if (aC.stringToBoolean(response)) aC.logged = true;
		alert(aC.logged);
	});
},
login: function(){
	var e = false,
	username = $("#lusername"),
	password = $("#lpassword");
	if ($.trim(username.val()) == "") { username.addClass('error'); e = true; } else username.removeClass('error');
	if ($.trim(password.val()) == "") { password.addClass('error'); e = true; } else password.removeClass('error');
	if (!e) {
		$('#f_login input').attr('disabled',true);
		$.mobile.changePage("ajax.php", {
			type: "post", 
			data: {login:true,username:$.trim(username.val()),password:$.trim(password.val())}
		});
	}
},
regValidate: function(){
	var e = false,
	username = $("#username"),
	password = $("#password"),
	name = $("#name"),
	email = $("#email"),
	hometown = $("#hometown"),
	city = $("#city"),
	bmonth = $("#bmonth"),
	bday = $("#bday"),
	byear = $("#byear"),
	usernameReg = /\W/,
	nameReg = /[A-Za-z'-]/,
	emailReg = /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}?$/i;

	if ($.trim(username.val()) == "") { username.addClass('error'); e = true; }
	else if (usernameReg.test($.trim(username.val()))) { username.addClass('error'); e = true; }
	else { aC.checkUsername($.trim(username.val())); if ($("#username").hasClass('error')) e = true; }
	if ($.trim(password.val()) == "") { password.addClass('error'); e = true; } else password.removeClass('error');

	if ($.trim(name.val()) == "") { name.addClass('error'); e = true; }
	else if (!nameReg.test($.trim(name.val()))) { name.addClass('error'); e = true; }
	else if ($.trim(name.val()).split(' ').length < 2) { name.addClass('error'); e = true; } else name.removeClass('error');

	if ($.trim(email.val()) == "") { email.addClass('error'); e = true; }
	else if (!emailReg.test($.trim(email.val()))) { email.addClass('error'); e = true; } else email.removeClass('error');

	if ($.trim(hometown.val()) == "") { hometown.addClass('error'); e = true; } else hometown.removeClass('error');
	if (bmonth.val() == 0) { bmonth.prev().addClass('error'); e = true; } else bmonth.prev().removeClass('error');
	if (bday.val() == 0) { bday.parent().addClass('error'); e = true; } else bday.parent().removeClass('error');
	if (byear.val() == 0) { byear.parent().addClass('error'); e = true; } else byear.parent().removeClass('error');
	
	if (e) return false;
	else return true;
},
checkUsername: function(uname) {
	$.get('ajax.php', {p:"username",username:uname}, function(data) {
		if (data > 0) $("#username").addClass('error');
		else $("#username").removeClass('error');
	});
},
logout: function() {
	$.post('ajax.php', {logout:true}, function() {
		$.mobile.changePage("#splash");
	});
}
};

$(document.documentElement).keydown(aC.onKeyDown);
$(document).ready(function(){ aC.init();
$("#lusername, #lpassword").focus(function(){
	aC.loginFocus = true;
}).blur(function(){
	aC.loginFocus = false;
});
$("#username, #password, #name, #email, #hometown, #city").live('focus',function(){
	aC.registerFocus = true;
}).live('blur',function(){
	aC.registerFocus = false;
});
$("#b_login").click(function(){
	if ($("#logincontent").is(":hidden")) $("#logincontent").slideDown();
	else aC.login();
});
$("#username").live('blur',function(){
	aC.checkUsername($.trim(this.value));
});
$("#b_register").live('click',function(){
	if (!aC.regValidate()) return;
	$('#f_register input').attr('disabled',true);
	$.mobile.changePage("ajax.php", {
		type: "post", 
		data: {register:true,username:$.trim($("#username").val()),password:$.trim($("#password").val()),name:$.trim($("#name").val()),email:$.trim($("#email").val()),hometown:$.trim($("#hometown").val()),city:$.trim($("#city").val()),gender:$.trim($("#gender").val()),bmonth:$("#bmonth").val(),bday:$("#bday").val(),byear:$("#byear").val()}
	});
});
$("#logout").live('click',function(){
	aC.logout();
});
});