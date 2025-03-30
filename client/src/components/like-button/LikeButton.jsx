import { useState } from "react";
import { useSetLiked } from "../../stores/recipeStore";

export default function LikeButton({recipe}) {
    const [liked, setLiked] = useState(recipe.liked ?? false);
    const setLikedAPI = useSetLiked();

    // if (recipe.liked) {
    //     setLiked(true);
    // }

    console.log(liked);

    const handleAddClick = async (id) => {
        const responseData = await setLikedAPI({id: id, mode: 'add'});

        console.log(responseData);

        if (responseData.id > 0) {
            setLiked(true);
        }
    }

    const handleDeleteClick = async (id) => {
        const responseData = await setLikedAPI({id: id, mode: 'delete'});

        if (responseData.id > 0) {
            setLiked(false);
        }
    }

    return (
        <>
            {(liked === false || liked === 0) && (
                <div className="post-favourite" onClick={() => handleAddClick(recipe.id)}>
                    <div className="hover:text-black"><i className="far fa-heart" aria-hidden="true"></i> {recipe.liked}</div>
                </div>
            )}

            {(liked === true || liked === 1) && (
                <div className="post-favourite" onClick={() => handleDeleteClick(recipe.id)}>
                    <div className="hover:text-black"><i className="fas fa-heart" aria-hidden="true"></i> {recipe.liked}</div>
                </div>
            )}
            
        </>
    )
}