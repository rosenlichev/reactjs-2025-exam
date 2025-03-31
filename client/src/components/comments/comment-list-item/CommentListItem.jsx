export default function CommentListItem({comment}) {
    return (
        <>
            <div className="comment-list-item">
                <div className="flex items-center justify-between">
                    <h3>{comment.comment_author}</h3>
                    <span>{comment.comment_date}</span>
                </div>
                <div>{comment.comment_content}</div>
            </div>
        </>
    )
}