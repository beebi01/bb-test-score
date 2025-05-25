function loadComments(spotId) {
    const commentsContainer = document.getElementById("comments-" + spotId);
    commentsContainer.innerHTML = "";

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "comment.php?spot_id=" + spotId, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            commentsContainer.innerHTML = xhr.responseText;
        } else {
            commentsContainer.innerHTML = "<p>無法載入留言，請稍後再試。</p>";
        }
    };
    xhr.onerror = function() {
        commentsContainer.innerHTML = "<p>載入留言時出錯，請稍後再試！</p>";
    };
    xhr.send();
}
