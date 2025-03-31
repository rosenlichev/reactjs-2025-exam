import parse from 'html-react-parser';
import { Link, useNavigate, useParams } from "react-router";
import { useRecipe } from "../../stores/recipeStore";
import useAuth from '../../hooks/useAuth';
import Comments from '../comments/Comments';

export default function RecipeDetails() {
    const { isAuthenticated } = useAuth();
    const { id } = useParams();
    const { recipe } = useRecipe(id);

    return (
        <>
            <div className="page-wrapper">
                <section>
                    {recipe && (
                    <>    
                            <h1 className="relative title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">
                                {recipe.name}

                                {isAuthenticated === true && (
                                    <Link to={`/recipe-edit/${recipe.id}`} className="absolute right-0 bottom-[-45px] p-2 text-sm text-white rounded" style={{backgroundColor: '#03835a'}}>Edit recipe</Link>
                                )}
                            </h1>
                            
                            {(recipe.preparationTime !== '' || recipe.servings !== '' || recipe.categories.length > 0) && (
                                <div className="recipe-info flex flex-col">
                                    {(recipe.preparationTime !== '' || recipe.servings !== '') && (
                                        <div className="recipe-info-top grid grid-cols-3">
                                            {recipe.preparationTime !== '' && (
                                                <div className="flex flex-col">
                                                    <h3 className="text-xl !font-semibold font-roboto-condensed text-black">Active Time:</h3>
                                                    <span className="text-sm">{recipe.preparationTime}</span>
                                                </div>
                                            )}
                                            {recipe.preparationTime !== '' && (
                                                <div className="flex flex-col">
                                                    <h3 className="text-xl !font-semibold font-roboto-condensed text-black">Total Time:</h3>
                                                    <span className="text-sm">{recipe.preparationTime}</span>
                                                </div>
                                            )}
                                            {recipe.servings !== '' && (
                                                <div className="flex flex-col">
                                                    <h3 className="text-xl !font-semibold font-roboto-condensed text-black">Servings:</h3>
                                                    <span className="text-sm">{recipe.servings}</span>
                                                </div>
                                            )}
                                        </div>
                                    )}

                                    {(recipe.categories && recipe.categories.length > 0) && (
                                        <div className="recipe-info-bottom flex flex-col">
                                            <h3 className="text-xl !font-semibold font-roboto-condensed text-black">Categories:</h3>
                                            <div className="flex gap-4">
                                                {recipe.categories.map(category => <span className="text-sm underline" key={category.id}>{category.name}</span>)}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            )}

                            <section className="recipe">
                                {recipe.image && recipe.image !== '' && (
                                    <div className="flex items-center justify-center">
                                        <img src={recipe.image} alt={recipe.name} className="mb-10 rounded-3xl" />
                                    </div>
                                )}

                                {recipe.shortDescription && recipe.shortDescription !== '' && (
                                    <div className="mb-10 text-xl text-center">
                                        {recipe.shortDescription}
                                    </div>
                                )}

                                {recipe.description && recipe.description !== '' && (
                                    <>
                                        <h2 className="title-decoration-2 text-2xl font-roboto-mono">Description</h2>
                                        <div className="mb-10 text-xl text-center">
                                            {recipe.description}
                                        </div>
                                    </>
                                )}

                                {recipe.ingredients && recipe.ingredients.length > 0 && (
                                    <>
                                        <h2 className="title-decoration-2 mb-4 text-2xl font-roboto-mono">Ingredients</h2>
                                        <div className="mb-10 text-xl">
                                            <ul>
                                                {recipe.ingredients.map((ingredient, index) => <li key={index} className="max-w-[440px]">{ingredient}</li>)}
                                            </ul>
                                        </div>
                                    </>
                                )}

                                {recipe.preparation && recipe.preparation !== '' && (
                                    <>
                                        <h2 className="title-decoration-2 mb-5 text-2xl font-roboto-mono">Preparation</h2>
                                        <div className="preparation mb-10 text-xl">
                                            {parse(recipe.preparation)}
                                        </div>
                                    </>
                                )}
                            </section>

                            <Comments recipe_id={recipe.id} comments={recipe.comments} />
                        </>  
                    )}
                </section>
            </div>
        </>
    );
}