(function()){
  var popupCenter = function(url, title, width, height){
    var popupWidth = width || 640;
    var popupHeight = height || 320;
    var windowTop = window.screenTop || window.screenY;
    var windowLeft = window.screenLeft || window.screenX;
    var windowWidth = window.innerWidth || document.documentElement.clientWidth;
    var windowHeight = window.innerHeight || document.documentElement.clientHeight;
    var popupWidth = 640;
    var popupHeight = 320;
    var popupTop = windowLeft + windowWidth / 2 - popupWidth / 2;
    var popupLeft = windowTop + windowHeight / 2 - popupHeight / 2;
    var popup = window.open(url, title, 'scrollbars=yes, width=' + popupWidth + ', height=' + popupHeight +', top=' + popupTop +', left=' + popupLeft + '');
    popup.focus();
    return true;
  };

  document.querySelector('.share_twitter').addEventListener('click', function(e){
    e.preventDefault();
    var url = this.getAttribute('data-url');
    var shareUrl = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(document.title) +
                   "&via=JVN-Network_fr" + "&url=" + encodeURIComponent(url);
    popupCenter(shareUrl, "Partager sur Twitter");
  });

  document.querySelector('.share_twitter').addEventListener('click', function(e){
    e.preventDefault();
    var url = this.getAttribute('data-url');
    var shareUrl = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url);
    popupCenter(shareUrl, "Partager sur Facebook");
  });

  document.querySelector('.share_gplus').addEventListener('click', function(e){
    e.preventDefault();
    var url = this.getAttribute('data-url');
    var shareUrl = "https://plus.google.com/share?url=" + encodeURIComponent(url);
    popupCenter(shareUrl, "Partager sur Google Plus");
  });

  document.querySelector('.share_linkedin').addEventListener('click', function(e){
    e.preventDefault();
    var url = this.getAttribute('data-url');
    var shareUrl = "https://www.linkedin.com/shareArticle?url=" + encodeURIComponent(url);
    popupCenter(shareUrl, "Partager sur Linkedin");
  });
}();
