const title = $("#title");
const sky = $(".parallax-bg-sky").eq(0);
const cloudLeft1 = $(".parallax-bg-cloud-left1").eq(0);
const cloudLeft2 = $(".parallax-bg-cloud-left2").eq(0);
const cloudLeft3 = $(".parallax-bg-cloud-left3").eq(0);
const cloudLeft4 = $(".parallax-bg-cloud-left4").eq(0);
const cloudTop = $(".parallax-bg-cloud-top").eq(0);
const cloudRight1 = $(".parallax-bg-cloud-right1").eq(0);
const cloudRight2 = $(".parallax-bg-cloud-right2").eq(0);

const mountainLeft1 = $(".parallax-bg-mountain-left1").eq(0);
const mountainLeft2 = $(".parallax-bg-mountain-left2").eq(0);
const mountainRight = $(".parallax-bg-mountain-right1").eq(0);
const mountainBothSide = $(".parallax-bg-mountain-both-side").eq(0);
const mc = $(".parallax-bg-girl").eq(0);

const meadow = $(".parallax-bg-meadow").eq(0);

let fontSizeStr = title.css("font-size");
let fontSize = parseInt(fontSizeStr, 10);
let prevScroll = 0;
let textValue = fontSize;

$(window).scroll(() => {
  let value = $(window).scrollTop();

  let smoothValue = value * 0.325;

  title.css("margin-top", value * 2.5 + "px");
  cloudTop.css("left", smoothValue * 2.5 + "px");
  cloudLeft1.css("left", smoothValue * 2.5 + "px");
  cloudLeft2.css("left", smoothValue * 2.5 + "px");
  cloudLeft3.css("left", smoothValue * 2.5 + "px");
  cloudLeft4.css("left", smoothValue * 2.5 + "px");
  cloudRight1.css("left", smoothValue * 2.5 + "px");
  cloudRight2.css("left", smoothValue * 2.5 + "px");
  mc.css("top", smoothValue * 2.5 + "px");

  // Calculate new scale and translate values
  let scaleValue = 1 + value * 0.001;
  let translateZValue = -20 + value * 0.1;

  // Increase font size when scrolling down
  if (value > prevScroll) {
    let textValue = fontSize + value * 0.2;
    title.css("font-size", textValue + "px");
    if (parseInt(title.css("font-size")) > 330) {
      title.css("font-size", "1px");
    }
  }
  // when scrolling up
  else {
    title.css("font-size", fontSize + "px");
  }

  prevScroll = value;
  // Update transform property
  mountainBothSide.css({
    transform: `translateZ(${translateZValue}px) scale(${scaleValue})`,
  });
  mountainLeft1.css({
    transform: `translateZ(${translateZValue}px) scale(${scaleValue})`,
  });
  mountainLeft2.css({
    transform: `translateZ(${translateZValue}px) scale(${scaleValue})`,
  });
  mountainRight.css({
    transform: `translateZ(${translateZValue}px) scale(${scaleValue})`,
  });
  meadow.css({
    transform: `translateZ(${translateZValue}px) scale(${scaleValue})`,
  });

});

// text scroll fade in animation
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      $(entry.target).addClass("show");
    } else {
      $(entry.target).removeClass("show");
    }
  });
});

const hiddenElements = $(".hidden");
hiddenElements.each((index, element) => {
  observer.observe(element);
});
