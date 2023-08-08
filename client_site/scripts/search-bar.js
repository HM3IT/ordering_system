const delayTimer = 700;
let timeoutId;

function debounce(func, delay) {
  clearTimeout(timeoutId);
  timeoutId = setTimeout(func, delay);
}

const itemContainer = $(".matched-item-lists");
const items = itemContainer.find("li");

const nothingLi = $("<li><div><span>Found nothing</span></div></li>")
  .addClass("found-nothing")
  .css("width", "500px")
  .hide();

itemContainer.prepend(nothingLi);

const searchItem = () => {
  debounce(function () {
    const searchBox = $("#search-item").val().toUpperCase();
    if (searchBox.length < 1) {
      items.hide();
      nothingLi.hide();
    } else {
      let isFound = false;
      items.each(function () {
        const itemTitle = $(this).find(".item-title");
        if (itemTitle) {
          if (itemTitle.text().toUpperCase().includes(searchBox)) {
            $(this).show();
            isFound = true;
          } else {
            $(this).hide();
          }
        }
      });
      if (!isFound) {
        nothingLi.show();
      } else {
        nothingLi.hide();
      }
    }
  }, delayTimer);
};

$("#search-item").on("keyup", searchItem);
