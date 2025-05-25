function showCommentBox(spotId) {
    var commentBox = document.getElementById("comment-box-" + spotId);
    if (commentBox) {
        commentBox.style.display = "block";
        loadComments(spotId);
    }
}

function closeCommentBox(spotId) {
    var commentBox = document.getElementById("comment-box-" + spotId);
    if (commentBox) {
        commentBox.style.display = "none";
    }
}
