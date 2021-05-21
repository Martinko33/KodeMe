// var links = document.getElementsByClassName('link')
// for(var i = 0; i <= links.length; i++)
//     addClass(i)
//
//
// function addClass(id){
//     setTimeout(function(){
//         if(id > 0) links[id-1].classList.remove('hover')
//         links[id].classList.add('hover')
//     }, id*750)
// }

const hambAd = document.querySelector("#hamb-admin");
const menuMobile = document.querySelector(".hamburger");
hambAd.addEventListener("click", function(){
    if(!menuMobile.classList.contains("show")){
        menuMobile.classList.add('show');
    }
    else{
        menuMobile.classList.remove('show')
    }

})