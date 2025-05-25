let page = 1;

window.onscroll = function() {
    if (document.documentElement.scrollHeight - window.innerHeight === window.scrollY) {
        page++;
        loadMoreSpots(page);
    }
};

function loadMoreSpots(page) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "load_spots.php?page=" + page, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.querySelector(".main-content").insertAdjacentHTML('beforeend', xhr.responseText);
        }
    };
    xhr.onerror = function() {
        alert("加載更多景點時出錯，請稍後再試！");
    };
    xhr.send();
}
