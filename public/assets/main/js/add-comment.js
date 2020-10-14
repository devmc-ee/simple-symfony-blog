;(function () {
    let xhr;
    const submitBtn = document.getElementById('comment_save');
    const settings = window.blogData;
    const commentsContainer = document.querySelector('.comments-container');
    const commentContentInput = document.getElementById('comment_content');
    const noCommentNotice = document.querySelector('.no-comments-alert');
    submitBtn.addEventListener('click', e => makeRequest(e));

    function makeRequest(event) {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            console.error('Sorry, cannot create the instance of the httpRequest, ' +
                'the comments will be processed with regular http processing');
            return false;
        }
        event.preventDefault();

        const comment_content = commentContentInput.value;

        if (!comment_content.replace(/^\s+|\s+$/gm, '')) {
            console.log('empty');
            return false;
        }

        const data = {
            "comment_content": comment_content,
            "post_id": settings.post_id.toString(),
            "last_comment_id": getLastCommentId(),
        };

        xhr.onreadystatechange = processResponse;
        xhr.open('POST', settings.ajax_url, true);
        xhr.setRequestHeader('Content-type', 'application/json;charset=UTF-8');
        xhr.send(JSON.stringify(data));
    }

    function processResponse() {

        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {

                commentContentInput.value = '';

                const response = JSON.parse(xhr.responseText);
                addCommentBlock(response);
            } else {
                console.error('Woops... Something went wrong.');
                console.error(xhr.responseText);

            }
        }
    }

    function addCommentBlock(response) {

        let newCommentBlocks = '';
        if (response.last_comments
            && response.last_comments.length > 0) {
            setLastCommentId(response.last_comments[0].id);
        }
        if (response.comments_returned_count === 0) {
            const comment = response.last_comments;
            const commentDateTime = comment.created_at;
            newCommentBlocks = newCommentBlocks + `
<div class="bd-callout bd-callout-info card comment-${comment.id}">
                            <blockquote class="blockquote mb-0 ">
                                <p> ${comment.content}</p>
                                <footer class="blockquote-footer text-muted small">
                                   id: ${comment.id}: Added at ${commentDateTime}
                                </footer>
                            </blockquote>
                        </div>`;
        } else {
            for (let comment of response.last_comments) {
                let commentDateTime = comment.created_at.date.substring(0, 16);
                newCommentBlocks = newCommentBlocks + `
<div class="bd-callout bd-callout-info card comment-${comment.id}">
                            <blockquote class="blockquote mb-0 ">
                                <p> ${comment.content}</p>
                                <footer class="blockquote-footer text-muted small">
                                   id: ${comment.id}: Added at ${commentDateTime}
                                </footer>
                            </blockquote>
                        </div>`;
            }
        }
        noCommentNotice.classList.add('d-none');
        newCommentBlocks += '<hr>';
        commentsContainer.innerHTML = newCommentBlocks + commentsContainer.innerHTML;
    }

    function getLastCommentId() {

        const lastCommentCreatedAtInput = document.querySelector('#last_comment_id');
        return lastCommentCreatedAtInput
            ? document.querySelector('#last_comment_id').value
            : '';

    }

    function setLastCommentId(lastCommentId) {

        const lastCommentIdInput = document.getElementById('last_comment_id');
        lastCommentIdInput.value = lastCommentId;
    }
})();