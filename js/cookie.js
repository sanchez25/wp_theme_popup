window.addEventListener('load', function () {

    function checkCookies() {
        let cookieDate = localStorage.getItem('cookieDate');
        let cookieNotification = document.getElementById('popup-block');
		
		if (!cookieNotification) {
			return;
		}
		
        let cookieBtnAccept = cookieNotification.querySelector('.close-popup');

        if( !cookieDate || (+cookieDate + 31536000000) < Date.now() ){
            cookieNotification.classList.add('show');
        }

        cookieBtnAccept.addEventListener('click', function(){
            localStorage.setItem( 'cookieDate', Date.now() );
            cookieNotification.classList.remove('show');
        })
    }
    checkCookies();
    
})