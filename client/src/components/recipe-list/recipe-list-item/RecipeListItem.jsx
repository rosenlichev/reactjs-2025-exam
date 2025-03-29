import { Link } from 'react-router'

export default function RecipeListItem({
    recipe
}) {
    return (
        <>
            <div className="single-recipe wow fadeInUp" data-wow-delay=".4s">
                <div className="post-thumb">
                    {recipe.image && recipe.image.trim() !== '' && (
                        
                        <img src={recipe.image} alt={recipe.name} />
                        
                    )}
                </div>
                <div className="post-content">
                    <div className="post-meta flex items-center justify-between">
                        <div className="post-author-date-area flex">
                            {recipe.author && recipe.author.trim() !== '' && (
                                <div className="post-author">
                                    <div className="hover:text-black">{recipe.author}</div>
                                </div>
                            )}
                            {recipe.category && (
                                <div className="post-date">
                                    <div className="hover:text-black">{recipe.category}</div>
                                </div>
                            )}
                        </div>
                        <div className="post-comment-share-area flex">
                            <div className="post-favourite">
                                <div className="hover:text-black"><i className="far fa-heart" aria-hidden="true"></i> {recipe.liked}</div>
                            </div>
                            <div className="post-comments">
                                <div className="hover:text-black"><i className="far fa-comment-alt" aria-hidden="true"></i> {recipe.comments}</div>
                            </div>
                        </div>
                    </div>
                    <Link to={`/recipe/${recipe.id}`} className="block mt-[10px] text-3xl font-roboto-condensed text-black">{recipe.name}</Link>
                </div>
            </div>
        </>
    );
}
