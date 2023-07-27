let reviewBox = document.getElementById("review-box-form");

let star = reviewBox.getElementsByClassName("star-rating");
let showValue = reviewBox.getElementsByClassName("rating-value")[0];
for (let i = 0; i < star.length; i++) {
  star[i].addEventListener("click", function() {
    showValue.innerHTML = this.value + " out of 5";
  });
}