

function isLogin(){
	if(window.sessionStorage.getItem('isLogin')!='ok'){
		location.replace('./errorPage.html');
	}

}

