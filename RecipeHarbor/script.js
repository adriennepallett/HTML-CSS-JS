function Accordian(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
      x.className += "w3-show";
    } else { 
      x.className = x.className.replace("w3-show", "");
    }
}

function mobileMenu() {
  var x = document.getElementById("nav-links-mobile");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}