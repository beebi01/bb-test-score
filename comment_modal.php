<!-- Modal 彈出視窗 -->
<div id="comment-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">✖</span>
        <h3>留言區</h3>
        <form id="comment-form" enctype="multipart/form-data">
            <input type="hidden" id="modal-spot-id" name="spot_id">
            <textarea name="comment" id="comment-text" placeholder="輸入留言..." required></textarea>
            <input type="file" name="image" id="image-input" accept="image/*">
            <img id="preview" class="preview-img">
            <button type="submit">送出留言</button>
        </form>
    </div>
</div>
