// Optimizing the searching process
const delayTimer = 700;
let timeoutId;

// callback function:func
function debounce(func, delay) {
  clearTimeout(timeoutId);
  timeoutId = setTimeout(func, delay);
}

const itemContainer = document.querySelector(".matched-item-lists");
const items = itemContainer.querySelectorAll("li");

const nothingLi = document.createElement("li");
nothingLi.classList.add("found-nothing");
nothingLi.innerHTML = "<div><span>Found nothing</span></div>";
nothingLi.style.width = "500px";

itemContainer.insertBefore(nothingLi, items[0]);

const searchItem = () => {
  debounce(function () {
    const searchBox = document.getElementById("search-item").value.toUpperCase();
    if (searchBox.length < 1) {
      items.forEach((item) => {
        nothingLi.style.display = "none";
        item.style.display = "none";
      });
    } else {
      let isFound = false;
      // Create a new nothingLi element for the "found nothing" message

      items.forEach((item) => {
        const itemTitle = item.querySelector(".item-title");
        if (itemTitle) {

          if (itemTitle.textContent.toUpperCase().includes(searchBox) ) {
            item.style.display = "block";
            isFound = true;
          } else {
            item.style.display = "none";
          }
        }
      });
      if (!isFound) {
        nothingLi.style.display = "block";
      } else {
        nothingLi.style.display = "none";
      }
    }
  }, delayTimer);
};
