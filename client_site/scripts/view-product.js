const mainImg = document.querySelector("#main-img img");
const smallImgs = document.querySelectorAll(".small-img-group img");

smallImgs.forEach((img) => {
  img.addEventListener("click", function () {
     const imgSrc = img.getAttribute("src"); 
    mainImg.setAttribute('src', imgSrc);
  });
});