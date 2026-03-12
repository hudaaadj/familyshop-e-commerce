let images = document.querySelectorAll(".image");
let buttons = document.querySelectorAll(".btn");
let currentImageIndex = 0;

buttons[currentImageIndex].style.backgroundColor = "black";

function showImage(index) {
  images.forEach((img, i) => {
    img.style.display = i === index ? "block" : "none";
  });
  buttons.forEach((btn, i) => {
    btn.style.backgroundColor = i === index ? "black" : "rgb(183, 183, 213";
  });
}

function left() {
  currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
  showImage(currentImageIndex);
}

function right() {
  currentImageIndex = (currentImageIndex + 1) % images.length;
  showImage(currentImageIndex);
}

function selectImage(index) {
  currentImageIndex = index;
  showImage(currentImageIndex);
}

buttons.forEach((button, index) => {
  button.addEventListener('click', () => selectImage(index));
});
