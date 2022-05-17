// flash message pop
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        document.querySelector(".flash-message").style.cssText =
            "display: none;";
    }, 3000);
});
