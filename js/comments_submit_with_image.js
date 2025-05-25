function submitCommentWithImage(spotId, commentText, imageFile) {
    const formData = new FormData();
    formData.append('spot_id', spotId);
    formData.append('comment', commentText);
    formData.append('image', imageFile); // input type="file"

    fetch('submit_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert("留言成功！");
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
