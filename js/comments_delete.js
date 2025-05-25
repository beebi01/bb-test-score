function deleteComment(commentId, spotId) {
    if (!confirm('確定要刪除這則留言嗎？')) {
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    alert("留言刪除成功！");
                    loadComments(spotId);
                } else {
                    alert("留言刪除失敗：" + response.message);
                }
            } catch (e) {
                alert("伺服器回傳格式錯誤");
            }
        } else {
            alert("留言刪除時發生錯誤，狀態碼：" + xhr.status);
        }
    };

    xhr.onerror = function() {
        alert("留言刪除時發生錯誤，請稍後再試！");
    };

    xhr.send("comment_id=" + encodeURIComponent(commentId));
}
