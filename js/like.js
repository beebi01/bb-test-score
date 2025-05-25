function likePost(spotId) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "likes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            let likeCountXhr = new XMLHttpRequest();
            likeCountXhr.open("GET", "get_like_count.php?spot_id=" + spotId, true);
            likeCountXhr.onload = function() {
                if (likeCountXhr.status === 200) {
                    let likeCount = likeCountXhr.responseText;
                    let likeButton = document.querySelector("#like-button-" + spotId);
                    likeButton.innerHTML = "👍 喜歡 (" + likeCount + ")";
                }
            };
            likeCountXhr.onerror = function() {
                alert("獲取點讚數據時出錯，請稍後再試！");
            };
            likeCountXhr.send();
        }
    };

    xhr.onerror = function() {
        alert("點讚時出錯，請稍後再試！");
    };

    xhr.send("spot_id=" + spotId);
}
