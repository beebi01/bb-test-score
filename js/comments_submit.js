function submitComment(spotId) {
    const comment = document.getElementById("comment-text-" + spotId).value;

    if (comment.trim() === "") {
        alert("請輸入留言內容！");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    const data = "spot_id=" + spotId + "&comment=" + encodeURIComponent(comment);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                alert("留言提交成功！");
                document.getElementById("comment-text-" + spotId).value = "";
                loadComments(spotId);
            } else {
                alert("留言提交失敗，請稍後再試！");
            }
        } else {
            alert("留言提交時出錯！");
        }
    };

    xhr.onerror = function() {
        alert("留言提交時出錯，請稍後再試！");
    };

    xhr.send(data);

    document.getElementById("comment-text-" + spotId).value = "";
}
