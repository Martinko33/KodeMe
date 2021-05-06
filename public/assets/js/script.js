const hamb = document.querySelector("#hamb");
const menuMobile = document.querySelector(".hamburger");
hamb.addEventListener("click", function(){
    if(!menuMobile.classList.contains("show")){
        menuMobile.classList.add('show');
    }
    else{
        menuMobile.classList.remove('show')
    }

})


const description = document.querySelector("#description");
const menuDescription = document.querySelector(".description");
description.addEventListener("click", function () {
    if(!menuDescription.classList.contains("show")){
        menuDescription.classList.add("show");
    }
    else{
        menuDescription.classList.remove("show")
    }
})

const support = document.querySelector("#support");
const menuSupport = document.querySelector(".support");
support.addEventListener("click", function () {
    if(!menuSupport.classList.contains("show")){
        menuSupport.classList.add("show");
    }
    else{
        menuSupport.classList.remove("show")
    }
})

const forum = document.querySelector("#forum");
const menuForum = document.querySelector(".forum");
forum.addEventListener("click", function () {
    if(!menuForum.classList.contains("show")){
        menuForum.classList.add("show");
    }
    else{
        menuForum.classList.remove("show")
    }
})

const avis = document.querySelector("#avis");
const menuAvis = document.querySelector(".avis");
avis.addEventListener("click", function () {
    if(!menuAvis.classList.contains("show")){
        menuAvis.classList.add("show");
    }
    else{
        menuAvis.classList.remove("show")
    }
})

