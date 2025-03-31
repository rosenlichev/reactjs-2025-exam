import parse from 'html-react-parser';
import { useEffect, useState } from "react";
import useAuth from "../../hooks/useAuth";
import CommentListItem from "./comment-list-item/CommentListItem";
import { useAddComment } from "../../stores/recipeStore";

export default function Comments({recipe_id, comments}) {
    const { isAuthenticated } = useAuth();
    const [recipeComments, setRecipeComments] = useState([]);
    const [message, setMessage] = useState("");
    const [isError, setIsError] = useState(false);
    const { addComment  } = useAddComment();
    const [formData, setFormData] = useState({
        comment_content: "",
        id: 0
    });

    useEffect(() => {
        setRecipeComments(comments);

        setFormData((prevData) => ({
            ...prevData,
            id: recipe_id,
        }));
    }, [recipe_id]);

    const handleChange = (event) => {
        const { name, value } = event.target;

        setFormData((prevData) => ({
        ...prevData,
        [name]: value, // Update the specific field dynamically
        }));
    };

    const handleCommentSubmit = async (event) => {
        event.preventDefault();

        const responseData = await addComment(formData);
        
        if (responseData.status == 400) {
            const data = await responseData.json();
            setMessage(data.message);
            setIsError(true);
        } else {
            const data = await responseData.json();
            
            setRecipeComments(data.comments ?? []);
            setMessage(data.message);
            setIsError(false);
        }
    }

    return (
        <>
            <section>
                <h3 className="title-decoration-2 mb-5 text-2xl font-roboto-mono">Comments</h3>
                <div className="comments-section grid grid-cols-2">
                    <div className="comments">
                        <div className="comments-list">
                            {Array.isArray(recipeComments) && recipeComments.length > 0
                                ? recipeComments.map(comment => <CommentListItem key={comment.id} comment={comment} />)
                                : <p className="text-2xl text-center">This recipe does not have any comments.</p>
                            }
                        </div>
                    </div>

                    {isAuthenticated === true && (
                        <div className="comments-form">
                            <section className="auth">
                                <form id="comment" onSubmit={handleCommentSubmit}>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="comment-content">Write your comment:</label>
                                        <textarea id="comment-content" name="comment_content" onChange={handleChange}></textarea>
                                    </div>

                                    {message !== '' && (
                                        <>
                                            {isError === true && (
                                                <div className="p-4 rounded bg-red-300 text-red-900">
                                                    {parse(message)}
                                                </div>
                                            )}
                                            {isError === false && (
                                                <div className="p-4 rounded bg-green-300 text-green-900">
                                                    {parse(message)}
                                                </div>
                                            )}
                                        </>
                                    )}

                                    <div className="flex flex-col gap-2">
                                        <button type="submit">Comment</button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    )}
                </div>
            </section>
        </>
    )
}