
$(document).ready(function(){
  var nAgt = navigator.userAgent;
  var nameOffset,verOffset,ix;

  if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
   browserName = "Chrome";
  }
  // In Safari, the true version is after "Safari" or after "Version" 
  else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
   browserName = "Safari";
       $('.rating-star-yellow > label.product-block-half').css('margin-left','-5px !important')
  }

  console.log(''
   +'Browser name  = '+browserName+'<br>'
  )
});